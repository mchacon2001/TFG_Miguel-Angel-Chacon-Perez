import { RestServiceConnection } from "../restServiceConnection";

export class LoginService extends RestServiceConnection {
  authUserCredentials = async (username: string, password: string) => {
    this.response = await this.makeRequest({
      method: "POST",
      url: "/login_check",
      data: {
        username: username,
        password: password,
      },
    });
    return this;
  };

  /**
   * Request to send an email with the code to recover the account password:
   * 
   * @param username (string) (NOT NULL)
   * @returns response
   */
  sendEmailForgotPassword = async (username: string) => {
    this.response = await this.makeRequest({
      method: "POST",
      url: "/send-email",
      data: {
        publicUrl: true,
        email: username,
      },
    });
    return this;
  };

  
  /**
   * Request to change the account password with the token provided:    
   * 
   * @param query_token (string) (NOT NULL)
   * @param password (string) (NOT NULL)
   * @param password_confirmation (string) (NOT NULL)
   * @returns response
   */
  resetForgotPassword = async (query_token: string, password: string, password_confirmation: string) => {
    this.response = await this.makeRequest({
      method: "POST",
      url: "/reset-password",
      data: {
        publicUrl: true,
        query_token: query_token,
        password: password,
        password_confirmation: password_confirmation,
      },
    });
    return this;
  };


  registerUser = async (
    name: string,
    email: string,
    password: string,
    sex: string,
    targetWeight: number,
    birthdate: string,
    height: number,
    weight: number,
    toGainMuscle: boolean,
    toLoseWeight: boolean,
    toMaintainWeight: boolean,
    toImprovePhysicalHealth: boolean,
    toImproveMentalHealth: boolean,
    fixShoulder: boolean,
    fixKnees: boolean,
    fixBack: boolean,
    rehab: boolean
   ) => {
     this.response = await this.makeRequest({
       method: "POST",
       url: "/register",
       data: {
         name: name,
         email: email,
         password: password,
         sex: sex,
         targetWeight: targetWeight,
         birthdate: birthdate,
          height: height,
          weight: weight,
          toGainMuscle: toGainMuscle,
          toLoseWeight: toLoseWeight,
          toMaintainWeight: toMaintainWeight,
          toImprovePhysicalHealth: toImprovePhysicalHealth,
          toImproveMentalHealth: toImproveMentalHealth,
          fixShoulder: fixShoulder,
          fixKnees: fixKnees,
          fixBack: fixBack,
          rehab: rehab,
          publicUrl: true
        },
    });
    return this;
}
}
