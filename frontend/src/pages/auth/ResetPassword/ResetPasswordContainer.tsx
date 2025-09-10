import classNames from "classnames";
import { FC, ReactNode } from "react";
import Card, { CardBody } from "../../../components/bootstrap/Card";
import useDarkMode from "../../../hooks/useDarkMode";
import Page from "../../../layout/Page/Page";
import PageWrapper from "../../../layout/PageWrapper/PageWrapper";
import { Link } from "react-router-dom";
import { ArrowBack } from "../../../components/icon/material-icons";

interface LoginResetPasswordContainerProps {
	children: ReactNode;
}

export const LoginResetPasswordContainer: FC<LoginResetPasswordContainerProps> = ({ children }) => {

	const { darkModeStatus } = useDarkMode();

	return (
		<PageWrapper
			title={'Login'}
			className='image-background'>
			<Page className='p-0'>
				<div className='row h-100 align-items-center justify-content-center'>
					<div className='col-xl-4 col-lg-6 col-md-8 shadow-3d-container'>
						<Card className='position-relative shadow-3d-dark' data-tour='login-page'>
							<CardBody>
								<div className='text-center my-4'>
									<img src='/logo.png' height={100} className="mb-4" alt="logo" />

									<div className="position-absolute top-0 start-0 m-4">
										<Link
											to='/'
											className={classNames(
												'text-decoration-none fw-bold display-2 justify-content-center d-flex',
												{
													'text-dark': !darkModeStatus,
													'text-light': darkModeStatus,
												},
											)}
										>
											<ArrowBack fontSize={'40px'} />
										</Link>
									</div>

									<div className="d-flex flex-row gap-4 justify-content-center">
										<span className="fs-2">Resetear la contraseña</span>
									</div>
								</div>

								{children}
							</CardBody>
						</Card>
					</div>
				</div>
			</Page>
		</PageWrapper>
	)
}