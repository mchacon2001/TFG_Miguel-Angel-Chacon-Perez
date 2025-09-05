import { FC, Fragment, useState } from "react";
import { useNavigate } from "react-router-dom";
import Card, { CardHeader, CardTitle } from "../../../components/bootstrap/Card";
import { UserService } from "../../../services/users/userService";
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { usersMenu } from "../../../menu";
import UserForm from "../UserForm";
import SvgAccountCircle from "../../../components/icon/material-icons/AccountCircle";
import Page from "../../../layout/Page/Page";
import Button from "../../../components/bootstrap/Button";
import { SubheaderSeparator } from "../../../layout/SubHeader/SubHeader";

const UserCreate: FC = () => {

	const navigate = useNavigate();
	const [loading, setLoading] = useState<boolean>(false);

	const handleCreation = async (values: any) => {
		try {
			setLoading(true)
			let response = await (await (new UserService()).createUser(values)).getResponseData();
			if (response.success) {
				toast.success(response.message);
				navigate(usersMenu.users.path, { replace: true })
			} else {
				toast.error(response.message);
			}
		} catch (error: any) {
			toast.error('Error al crear el usuario');
		} finally {
			setLoading(false);
		}
	};

	return (
		<Fragment>
			<Page container='xxl'>
				<Card className="col-md-8 m-auto">
					<CardHeader borderSize={1} className="d-flex justify-content-start">
						<Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
						<SvgAccountCircle fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
						<CardTitle className="fs-3 mb-0 ms-3">Crear Usuario</CardTitle>
					</CardHeader>
					<UserForm submit={handleCreation} isLoading={loading} />
				</Card>
			</Page>
		</Fragment>
	)
}

export default UserCreate;