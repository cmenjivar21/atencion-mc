<template>
  <div data-app>
    <alert-time-out
      :redirect="redirectSessionFinished"
      @redirect="updateTimeOut($event)"
    />
    <alert
      :text="textAlert"
      :event="alertEvent"
      :show="showAlert"
      @show-alert="updateAlert($event)"
      class="mb-2"
    />
    <v-card class="p-3">
      <v-container>
        <h2>Roles</h2>
        <div class="options-table">
          <v-btn
            rounded
            @click="addRecord()"
            title="Agregar"
            class="btn btn-normal"
          >
            Agregar
          </v-btn>
        </div>
        <v-col cols="12" sm="12" md="12" lg="4" xl="4" class="pl-0 pb-0 pr-0">
          <v-text-field
            class="mt-3"
            dense
            label="Buscar"
            type="text"
            v-model="options.search"
          ></v-text-field>
        </v-col>
      </v-container>
      <v-data-table
        :search="options.search"
        :headers="headers"
        :items="recordsFiltered"
        :options.sync="options"
        :loading="loading"
        item-key="id"
        sort-by="name"
        hide-default-footer
      >
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">
            mdi-pencil
          </v-icon>
        </template>
        <template v-slot:no-data>
          <v-icon small class="mr-2" @click="initialize"> mdi-refresh </v-icon>
        </template>
      </v-data-table>
    </v-card>

    <v-dialog v-model="dialog" max-width="90%" persistent>
      <v-card class="flexcard" height="100%">
        <v-card-title>
          <h1 class="mx-auto pt-3 mb-3 text-center black-secondary">
            {{ formTitle }}
          </h1>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-tabs fixed-tabs v-model="selectedTab">
              <v-tab> Información </v-tab>
              <v-tab> Permisos </v-tab>
            </v-tabs>
            <!-- Form -->
            <v-row class="pt-3" v-if="selectedTab == 0">
              <v-container>
                <v-col cols="12" sm="12" md="6" lg="4" xl="4">
                  <base-input
                    label="Nombre"
                    v-model="$v.editedItem.name.$model"
                    :validation="$v.editedItem.name"
                    validationTextType="default"
                  />
                </v-col>
              </v-container>
            </v-row>
            <!-- Form -->

            <!-- Permissions -->
            <v-row class="pt-3" v-if="selectedTab == 1">
              <permission
                :editedIndex="editedIndex"
                :permissionProps="editedItem.permissions"
                @update-permissions="editedItem.permissions = $event"
              />
            </v-row>
            <!-- Permissions -->
            <v-row>
              <v-col align="center">
                <v-btn
                  color="btn-normal no-uppercase mt-3"
                  rounded
                  @click="save"
                >
                  Guardar
                </v-btn>
                <v-btn
                  color="btn-normal-close no-uppercase mt-3"
                  rounded
                  @click="close"
                >
                  Cancelar
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import roleApi from "../../apis/roleApi";
import lib from "../../libs/function";

import {
  required,
  minLength,
  maxLength,
  email,
  helpers,
} from "vuelidate/lib/validators";

export default {
  data() {
    return {
      search: "",
      dialog: false,
      headers: [
        { text: "ROLE", value: "name" },
        { text: "ACCIONES", value: "actions", sortable: false },
      ],
      records: [],
      recordsFiltered: [],
      editedIndex: -1,
      title: "Usuarios",
      totalItems: 0,
      options: {},
      editedItem: {
        name: "",
        permissions: [],
      },
      defaultItem: {
        name: "",
        permissions: [],
      },
      roles: [],
      selectedTab: 0,
      loading: false,
      debounce: 0,
      textAlert: "",
      alertEvent: "success",
      showAlert: false,
      redirectSessionFinished: false,
      alertTimeOut: 0,
    };
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
  },

  // Validations
  validations: {
    editedItem: {
      name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(255),
      },
    },
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: false,
      dirty: false,
    },
    dialog(val) {
      val || this.close();
    },
    dialogBlock(val) {
      val || this.closeBlock();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    async initialize() {
      this.loading = true;
      this.$v.$reset();
      this.records = [];
      this.recordsFiltered = [];

      let requests = [this.getDataFromApi()];

      const responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
      });

      this.loading = false;
    },

    editItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.selectedTab = 0;
      this.dialog = true;
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async save() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      if (this.editedIndex > -1) {
        const edited = Object.assign(
          this.recordsFiltered[this.editedIndex],
          this.editedItem
        );

        const res = await roleApi
          .put(`/${edited.id}`, edited)
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible actualizar el registro.",
              "fail"
            );
            return;
          });

        this.updateAlert(true, "Registro actualizado correctamente.");
      } else {
        //Creating user
        const res = await roleApi.post(null, this.editedItem).catch((error) => {
          this.updateAlert(true, "No fue posible crear el registro.", "fail");
          return;
        });
        this.updateAlert(true, "NRegistro creado correctamente.");
      }

      this.close();
      this.initialize();
      return;
    },

    getDataFromApi() {
      this.loading = true;
      this.records = [];
      this.recordsFiltered = [];

      //debounce
      clearTimeout(this.debounce);
      this.debounce = setTimeout(async () => {
        const res = await roleApi.get().catch((error) => {
          this.updateAlert(
            true,
            "No fue posible realizar la búsqueda.",
            "fail"
          );
        });

        this.records = res.data.roles;
        this.recordsFiltered = res.data.roles;
        this.total = res.data.total;
        this.loading = false;
      }, 500);
    },

    addRecord() {
      this.dialog = true;
      this.editedIndex = -1;
      this.selectedTab = 0;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedItem.permissions = [];
    },

    updateAlert(show = false, text = "Alerta", event = "success") {
      this.textAlert = text;
      this.alertEvent = event;
      this.showAlert = show;
    },
  },
};
</script>
