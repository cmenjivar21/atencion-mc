import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const ticketApi = axios.create({
    baseURL: "/api/ticket",
});

// ticketApi.interceptors.request.use(interceptorRequest);
// ticketApi.interceptors.response.reject(interceptorReponse);

export default ticketApi;
