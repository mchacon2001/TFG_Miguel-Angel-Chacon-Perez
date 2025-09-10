import React from "react";
import img from "../assets/error_message.jpg";

type ErrorMessageProps = {
  error?: string;
}

const ErrorMessage: React.FC<ErrorMessageProps> = ({error}) => {
  return (
    <div className="container d-flex flex-column align-content-center justify-content-center mt-5">
      <div className={'error-message-title h2 text-center'}>
        {error ? ''+error : "Ha ocurrido un error al cargar los datos."}
      </div>
      <div className={'error-message-image text-center'}>
        <img width='380px' height='300px' src={img} alt="Error message" className="img-responsive" />
      </div>



    </div>
  );
};

export default ErrorMessage;