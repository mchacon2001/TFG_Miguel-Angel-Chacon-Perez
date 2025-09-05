import React, { Fragment } from "react";
import ErrorMessage from "../../components/ErrorMessage";
import {CardBody} from "../../components/bootstrap/Card";

type ErrorMessagePageProps = {
  error?: string;
}

const ErrorPageComponent: React.FC<ErrorMessagePageProps> = ({error}) => {
  return (
    <Fragment>
      <CardBody>
        <Fragment>{<ErrorMessage error={error}/>}</Fragment>
      </CardBody>
    </Fragment>
  );
};

export default ErrorPageComponent;