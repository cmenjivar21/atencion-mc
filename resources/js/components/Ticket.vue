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
        <h2>Ticket</h2>
        <div class="options-table">
          <v-btn
            rounded
            @click="addRecord()"
            title="Agregar"
            class="btn btn-normal"
          >
            <v-icon> mdi-plus </v-icon> Agregar
          </v-btn>
          <v-icon
            @click="deleteItem()"
            title="Eliminar"
            v-if="selected.length > 0"
          >
            mdi-delete
          </v-icon>
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
        v-model="selected"
        :single-select="false"
        show-select
        :search="options.search"
        :headers="headers"
        :items="recordsFiltered"
        :options.sync="options"
        :loading="loading"
        item-key="id"
        sort-by="id"
        :footer-props="{ 'items-per-page-options': [15, 30, 50, 100] }"
      >
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">
            mdi-pencil
          </v-icon>
          <v-icon small class="mr-2" @click="deleteItem(item)">
            mdi-delete
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
            <!-- Form -->
            <v-row class="pt-3">
              <h3>Información general</h3>
              <hr />
              <!-- client_name -->
              <v-col cols="12" sm="12" md="6">
                <base-input
                  label="Nombre Cliente"
                  v-model="$v.editedItem.client_name.$model"
                  :validation="$v.editedItem.client_name"
                  validationTextType="none"
                />
              </v-col>
              <!-- client_name -->

              <!-- client_last_name -->
              <v-col cols="12" sm="12" md="6">
                <base-input
                  label="Apellido Cliente"
                  v-model="$v.editedItem.client_last_name.$model"
                  :validation="$v.editedItem.client_last_name"
                  validationTextType="none"
                />
              </v-col>
              <!-- client_last_name -->

              <!-- client_dui -->
              <v-col cols="12" sm="12" md="6">
                <base-input
                  label="DUI"
                  v-model="$v.editedItem.client_dui.$model"
                  :validation="$v.editedItem.client_dui"
                  validationTextType="none"
                  v-mask="'########-#'"
                />
              </v-col>
              <!-- client_dui -->

              <!-- municipality_name -->
              <v-col cols="12" sm="12" md="6">
                <base-select-search
                  label="Municipio"
                  v-model.trim="$v.editedItem.municipality_name.$model"
                  :items="municipalities"
                  item="municipality_name"
                  :validation="$v.editedItem.municipality_name"
                />
              </v-col>
              <!-- municipality_name -->

              <!-- phone -->
              <v-col cols="12" sm="12" md="6">
                <base-input
                  label="Teléfono"
                  v-model="$v.editedItem.phone.$model"
                  :validation="$v.editedItem.phone"
                  validationTextType="none"
                  v-mask="'####-####'"
                />
              </v-col>
              <!-- phone -->

              <h3>Descripción del ticket</h3>
              <hr />

              <!-- name_sub_cat -->
              <v-col cols="12" sm="12" md="6">
                <base-select-search
                  label="Clasificación de solicitud"
                  v-model.trim="$v.editedItem.name_sub_cat.$model"
                  :items="subcategorie"
                  item="name_sub_cat"
                  :validation="$v.editedItem.name_sub_cat"
                />
              </v-col>
              <!-- name_sub_cat -->

              <!-- date -->
              <v-col cols="12" sm="12" md="3">
                <base-input
                  label="Fecha"
                  v-model="$v.editedItem.date.$model"
                  :validation="$v.editedItem.date"
                  validationTextType="none"
                  type="date"
                  :readonly="true"
                />
              </v-col>
              <!-- date -->

              <!-- time -->
              <v-col cols="12" sm="12" md="3">
                <base-input
                  label="Hora"
                  v-model="$v.editedItem.time.$model"
                  :validation="$v.editedItem.time"
                  validationTextType="none"
                  type="time"
                  :readonly="true"
                />
              </v-col>
              <!-- time -->

              <!-- description -->
              <v-col cols="12" sm="12" md="12">
                <base-text-area
                  label="Descripción"
                  v-model="$v.editedItem.description.$model"
                  :validation="$v.editedItem.description"
                  validationTextType="none"
                />
              </v-col>
              <!-- description -->

              <!-- solution -->
              <v-col cols="12" sm="12" md="12">
                <base-text-area
                  label="Solución"
                  v-model="$v.editedItem.solution.$model"
                  :validation="$v.editedItem.solution"
                  validationTextType="none"
                />
              </v-col>
              <!-- solution -->

              <!-- finished -->
              <v-col cols="12" sm="12" md="4">
                <v-checkbox
                  label="Finalizado"
                  v-model="$v.editedItem.finished.$model"
                  :validation="$v.editedItem.finished"
                  validationTextType="none"
                />
              </v-col>
              <!-- finished -->
            </v-row>
            <!-- Form -->
            <v-row>
              <v-col align="center">
                <v-btn
                  color="btn-normal no-uppercase mt-3"
                  rounded
                  @click="save"
                  :disabled="
                    editedItem.finished_letters == 'Finalizado' ? true : false
                  "
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

    <v-dialog v-model="dialogDelete" max-width="400px">
      <v-card class="h-100">
        <v-container>
          <h1 class="black-secondary text-center mt-3 mb-3">
            {{
              selected.length > 0 ? "Eliminar registros" : "Eliminar registro"
            }}
          </h1>
          <v-row>
            <v-col align="center">
              <v-btn
                color="btn-normal no-uppercase mt-3 mb-3 pr-5 pl-5 mx-auto"
                rounded
                @click="deleteItemConfirm"
                >Confirmar</v-btn
              >
              <v-btn
                color="btn-normal-close no-uppercase mt-3 mb-3"
                rounded
                @click="closeDelete"
              >
                Cancelar
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import ticketApi from "../apis/ticketApi";
import subCategorieApi from "../apis/subCategorieApi";
import userApi from "../apis/userApi";
import municipalityApi from "../apis/municipalityApi";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import BaseTextArea from "./base-components/BaseTextArea.vue";
import lib from "../libs/function";
import moment from "moment";

export default {
  components: { BaseTextArea },
  data() {
    return {
      search: "",
      selected: [],
      dialog: false,
      dialogDelete: false,
      headers: [
        { text: "Id Ticket", value: "id" },
        { text: "Nombre Cliente", value: "client_name" },
        { text: "Apellido Cliente", value: "client_last_name" },
        { text: "DUI", value: "client_dui" },
        { text: "Fecha creación", value: "created_at" },
        { text: "Operador", value: "user_name" },
        { text: "Estado", value: "finished_letters" },
        { text: "ACCIONES", value: "actions", sortable: false },
      ],
      records: [],
      recordsFiltered: [],
      editedIndex: -1,
      title: "Ticket",
      totalItems: 0,
      options: {},
      editedItem: {
        client_name: "",
        client_last_name: "",
        client_dui: "",
        description: "",
        solution: "",
        created_at: "",
        name_sub_cat: "",
        municipality_name: "",
        finished: "",
        date: "",
        time: "",
      },
      defaultItem: {
        client_name: "",
        client_last_name: "",
        client_dui: "",
        description: "",
        solution: "",
        phone: "",
        name_sub_cat: "",
        municipality_name: "",
        name: "",
        finished: "",
        date: "",
        time: "",
      },
      selectedTab: 0,
      loading: false,
      debounce: 0,
      textAlert: "",
      alertEvent: "success",
      showAlert: false,
      redirectSessionFinished: false,
      alertTimeOut: 0,
      subcategorie: [],
      users: [],
      municipalities: [],
    };
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi();
      },
      deep: true,
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  // Validations
  validations: {
    editedItem: {
      client_name: {
        required,
        minLength: minLength(1),
      },
      client_last_name: {
        required,
        minLength: minLength(1),
      },
      client_dui: {
        // required,
        // minLength: minLength(1),
      },
      description: {
        required,
        minLength: minLength(1),
      },
      solution: {
        required,
        minLength: minLength(1),
      },
      phone: {
        // required,
        // minLength: minLength(1),
      },
      name_sub_cat: {
        required,
        minLength: minLength(1),
      },
      municipality_name: {
        required,
        minLength: minLength(1),
      },
      finished: {
        // required,
        // minLength: minLength(1),
      },
      date: {
        required,
        minLength: minLength(1),
      },
      time: {
        required,
        minLength: minLength(1),
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

  mounted() {
    this.initialize();
  },

  methods: {
    async initialize() {
      this.loading = true;
      this.$v.$reset();
      this.records = [];
      this.recordsFiltered = [];
      this.editedItem = this.defaultItem;

      let requests = [
        this.getDataFromApi(),
        subCategorieApi.get(null, {
          params: { itemsPerPage: -1 },
        }),

        municipalityApi.get(null, {
          params: { itemsPerPage: -1 },
        }),
      ];

      const responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener el registro.", "fail");

        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          419
        );
      });

      if (responses) {
        this.subcategorie = responses[1].data.records;

        this.municipalities = responses[2].data.municipalities;
      }

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

        const { data } = await ticketApi
          .put(`/${edited.id}`, edited)
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible actualizar el registro.",
              "fail"
            );

            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
            this.close();
          });

        if (data.success) {
          this.updateAlert(true, data.message, "success");
        }
      } else {
        //Creating user
        const { data } = await ticketApi
          .post(null, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");

            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
            this.close();
          });

        if (data.success) {
          this.updateAlert(true, data.message, "success");
        }
      }

      this.close();
      this.initialize();
      return;
    },

    deleteItem(item = null) {
      console.log(item);
      if (item) {
        this.editedIndex = this.recordsFiltered.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.selected = [];
      }

      this.dialogDelete = true;
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async deleteItemConfirm() {
      const { data } = await ticketApi
        .delete(null, {
          params: {
            selected: this.selected,
            id: this.editedItem.id,
          },
        })
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible eliminar el registro.",
            "fail"
          );

          this.redirectSessionFinished = lib.verifySessionFinished(
            error.response.status,
            419
          );
          this.closeDelete();
        });

      if (data.success) {
        this.updateAlert(true, data.message, data.type ?? "success");
      }

      this.initialize();
      this.closeDelete();
    },

    getDataFromApi() {
      this.loading = true;
      this.records = [];
      this.recordsFiltered = [];

      //debounce
      clearTimeout(this.debounce);
      this.debounce = setTimeout(async () => {
        const { data } = await ticketApi
          .get(null, {
            params: this.options,
          })
          .catch((error) => {
            this.updateAlert(
              true,
              "No fue posible obtener los registros.",
              "fail"
            );
          });

        this.records = data.records;
        this.recordsFiltered = data.records;
        this.total = data.total;
        this.loading = false;
      }, 500);
    },

    addRecord() {
      this.dialog = true;
      this.editedIndex = -1;
      this.selectedTab = 0;
      this.editedItem = this.defaultItem;
      this.editedItem.date = moment().format("YYYY-MM-DD");
      //console.log(this.editedItem.date);
      this.editedItem.time = moment().format("HH:mm");
      this.editedItem.client_dui = "";
      this.$v.$reset();
    },

    updateAlert(show = false, text = "Alerta", event = "success") {
      this.textAlert = text;
      this.alertEvent = event;
      this.showAlert = show;
    },
  },
};
</script>
