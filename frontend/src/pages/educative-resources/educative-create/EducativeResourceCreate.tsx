import { FC, Fragment, useState } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from 'react-toastify';
import { educativeResourcesMenu } from "../../../menu";
import { Inventory2 } from "../../../components/icon/material-icons";
import { EducativeResourceService } from "../../../services/educativeResources/educativeResourcesService";
import EducativeResourceForm from "../EducativeResourceForm";
import Card, { CardBody, CardHeader, CardTitle } from "../../../components/bootstrap/Card";
import Button from "../../../components/bootstrap/Button";
import useHandleErrors from "../../../hooks/useHandleErrors";
import SubHeader, { SubHeaderLeft } from "../../../layout/SubHeader/SubHeader";
import Page from "../../../layout/Page/Page";

const EducativeResourceCreate: FC = () => {

	const { handleErrors } = useHandleErrors();
	const navigate = useNavigate();

	const [loading, setLoading] = useState<boolean>(false);

	const handleCreation = async (values: any) => {
		try {
			setLoading(true);
			let response = await (await (new EducativeResourceService()).createEducativeResource(values)).getResponseData();
			if (response.success) {
				toast.success(response.message);
				navigate(educativeResourcesMenu.educativeResources.path, { replace: true })
			} else {
				handleErrors(response);
			}
		} catch (error: any) {
			toast.error('Error al crear el recurso educativo');
		} finally {
			setLoading(false);
		}
	};

	return (
		<Fragment>
			<SubHeader>
				<SubHeaderLeft>
					<Button color="primary" isLink icon="ArrowBack" onClick={() => navigate(-1)} />
				</SubHeaderLeft>
			</SubHeader>
			<Page container='fluid'>
				<Card className="col-md-9 m-auto">
					<CardHeader borderSize={1} className="d-flex justify-content-start">
						<Inventory2 fontSize={'30px'} color="rgba(0, 0, 0, 0.3)" />
						<CardTitle className="fs-3 mb-0 ms-3">Crear Recurso Educativo</CardTitle>
					</CardHeader>
					<CardBody>
						<EducativeResourceForm submit={handleCreation} isLoading={loading} />
					</CardBody>
				</Card>
			</Page>
		</Fragment>
	)
}

export default EducativeResourceCreate;