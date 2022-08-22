import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const permissionApi = axios.create({
    baseURL: "/api/permission",
});

// permissionApi.interceptors.request.use(interceptorRequest);
// permissionApi.interceptors.response.reject(interceptorReponse);

export default permissionApi;
