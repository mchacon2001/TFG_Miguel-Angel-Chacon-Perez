import classNames from "classnames";
import { FC, ReactNode } from "react";
import { Link } from "react-router-dom";
import Card, { CardBody } from "../../../components/bootstrap/Card";
import useDarkMode from "../../../hooks/useDarkMode";
import Page from "../../../layout/Page/Page";
import PageWrapper from "../../../layout/PageWrapper/PageWrapper";
import { ArrowBack } from "../../../components/icon/material-icons";


interface LoginForgotPasswordContainerProps {
    children: ReactNode;
}

export const LoginForgotPasswordContainer: FC<LoginForgotPasswordContainerProps> = ({children}) => {

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
									<Link
										to='/'
										className={classNames(
											'text-decoration-none  fw-bold display-2 justify-content-center d-flex',
											{
												'text-dark': !darkModeStatus,
												'text-light': darkModeStatus,
											},
										)}>

										<div className="d-flex flex-row gap-4" style={{marginRight: '40px'}}>
											<ArrowBack fontSize={'40px'} />
											<span className="fs-1">Volver al login</span>
										</div>
									</Link>
								</div>
								<p className="d-flex justify-content-center"> Introduce el correo electrónico para reestablecer la contraseña enviandote un correo al mismo.</p>
								{children}
							</CardBody>
						</Card>
					</div>
				</div>
			</Page>
		</PageWrapper>
    )
}
