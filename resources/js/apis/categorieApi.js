import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const categorieApi = axios.create({
    baseURL: "/api/categorie",
});

// categorieApi.interceptors.request.use(interceptorRequest);
// categorieApi.interceptors.response.reject(interceptorReponse);

export default categorieApi;
