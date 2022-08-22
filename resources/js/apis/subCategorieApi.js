import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const subCategorieApi = axios.create({
    baseURL: "/api/subCategorie",
});

// subCategorieApi.interceptors.request.use(interceptorRequest);
// subCategorieApi.interceptors.response.reject(interceptorReponse);

export default subCategorieApi;
