import classNames from "classnames";
import { FC, ReactNode } from "react";
import { Link } from "react-router-dom";
import Card, { CardBody } from "../../components/bootstrap/Card";
import useDarkMode from "../../hooks/useDarkMode";
import Page from "../../layout/Page/Page";
import PageWrapper from "../../layout/PageWrapper/PageWrapper";

interface LoginContainerProps {
	children: ReactNode;
}

export const LoginContainer: FC<LoginContainerProps> = ({ children }) => {
	const { darkModeStatus } = useDarkMode();

	return (
		<PageWrapper title="Login" className="image-background">
			<Page className="p-0">
				<div className="row h-100 align-items-center justify-content-center">
					<div className="col-xl-4 col-lg-6 col-md-8 shadow-3d-container">
						<Card data-tour="login-page">
							<CardBody>
								<div className="text-center my-5">
									<div className="d-flex justify-content-center">
										<Link to="/" className="text-decoration-none">
											<img 
												src="/logo.png" 
												height={150} 
												alt="Logo de BrainyGym" 
												className={classNames('logo', {
													'text-dark': !darkModeStatus,
													'text-light': darkModeStatus,
												})} 
											/>
										</Link>
									</div>
								</div>
								{children}
							</CardBody>
						</Card>
					</div>
				</div>
			</Page>
		</PageWrapper>
	);
};
