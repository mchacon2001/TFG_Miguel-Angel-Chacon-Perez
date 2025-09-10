import classNames from "classnames";
import { FC, ReactNode } from "react";
import Card, { CardBody } from "../../../components/bootstrap/Card";
import useDarkMode from "../../../hooks/useDarkMode";
import Page from "../../../layout/Page/Page";
import PageWrapper from "../../../layout/PageWrapper/PageWrapper";
import { ArrowBack } from "../../../components/icon/material-icons";
import { Link } from "react-router-dom";
import Button from "../../../components/bootstrap/Button";

interface RegisterContainerProps {
    children: ReactNode;
}

export const RegisterContainer: FC<RegisterContainerProps> = ({ children }) => {

    const { darkModeStatus } = useDarkMode();

    return (
        <PageWrapper
            title={'Login'}
            className='image-background'>
            <Page className='p-0'>
                <div className='row h-100 align-items-center justify-content-center'>
                    <div className='col-xl-4 col-lg-6 col-md-8 shadow-3d-container'>
                        <Card className='shadow-3d-dark' data-tour='login-page'>
                            <CardBody>
                                <div className='text-center my-4'>
                                    <div
                                        className={classNames(
                                            'text-decoration-none fw-bold display-2 justify-content-center d-flex',
                                            {
                                                'text-dark': !darkModeStatus,
                                                'text-light': darkModeStatus,
                                            },
                                        )}>
                                        <div className="d-flex flex-row gap-4">
                                            <span className="fs-1">Crear usuario</span>
                                        </div>
                                    </div>
                                </div>
                                {children}
								
                                <div className='col-12 text-center'>
                                  <hr className='my-4' />
                                  <span className="d-block text-muted">¿Ya tienes una cuenta en <strong>BrainyGym</strong>?</span>
                                </div>
                                <div className='col-12 mt-4'>
                                    <Link to='/login'>
                                        <Button color='primary' className='w-100 py-3' type='button'>
                                            Iniciar sesión
                                        </Button>
                                    </Link>
                                </div>
                            </CardBody>
                        </Card>
                    </div>
                </div>
            </Page>
        </PageWrapper>
    )
}
