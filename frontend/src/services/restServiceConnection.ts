import axios, { AxiosRequestConfig, AxiosResponse } from "axios";
import { logout } from "../redux/authSlice";
import store, {RootState} from "../redux/store";
import {KEY} from "../redux/browser-storage";

export class RestServiceConnection {
    baseUrl: string | undefined;
    publicBaseUrl: string | undefined;
    response: AxiosResponse<any> | null = null;
    
    constructor() {
        this.baseUrl = process.env.REACT_APP_API_URL;
        this.publicBaseUrl = process.env.REACT_APP_API_PUBLIC_URL;
        this.response = null;
    }

    async makeRequest(config: AxiosRequestConfig, isAuth: boolean = false) {
        
        if(config.url !== undefined) {
            if(config.data?.publicUrl === true) {
                config.url = this.publicBaseUrl + config.url;
            } else {
                config.url = this.baseUrl + config.url;
            }
        }

        if(config.headers === undefined){
            config.headers = { 
                'Content-Type': 'application/json',
            }
        }

        if(isAuth){

            let {auth} = store.getState() as RootState;
            if(auth && auth?.user?.token) {
                Object.assign(config.headers, {
                    'Authorization': `Bearer ${auth?.user?.token}`
                })
            } else {
                store.dispatch(logout());
                window.location.reload();
            }
        }

        try {
            this.response = await axios(config);
        } catch (error: any){
            if(error.response?.status === 401 && (error.response?.data?.message === "Expired JWT Token" || error.response?.data?.message === "Credenciales incorrectas, compruebe que su nombre de usuario y contrase\u00f1a son correctos")){
               localStorage.removeItem(KEY);
               window.location.reload();
            }
        }

        return this.response;
    }

    getResponse() {
        return this.response;
    }

    getResponseData() {
        return this.response?.data;
    }

    getOnlyData() {
        return this.response?.data?.data;
    }

}