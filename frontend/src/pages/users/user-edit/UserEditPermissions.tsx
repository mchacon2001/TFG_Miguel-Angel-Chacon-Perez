import { useCallback, useState, FC, useEffect, Fragment } from "react";
import Accordion, { AccordionItem } from "../../../components/bootstrap/Accordion";
import Button from "../../../components/bootstrap/Button";
import Modal, { ModalBody, ModalFooter, ModalHeader, ModalTitle } from "../../../components/bootstrap/Modal";
import Spinner from "../../../components/bootstrap/Spinner";
import Checks from "../../../components/bootstrap/forms/Checks";
import { Permission, PermissionGroup, PermissionsApiResponse, RolePermission } from "../../../type/role-type";
import useFetch from "../../../hooks/useFetch";
import { PermissionService } from "../../../services/auth/permissionService";
import { UserService } from "../../../services/users/userService";
import { UserApiResponse } from "../../../type/user-type";
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Tooltips from "../../../components/bootstrap/Tooltips";
import Icon from "../../../components/icon/Icon";
import { handleConfirmationAlert } from "../../../utils/ConfirmationAlert";
import useHandleErrors from "../../../hooks/useHandleErrors";
import ErrorMessage from "../../../components/ErrorMessage";

interface RoleCreateModalProps {
	isOpen: boolean;
	setIsOpen(...args: unknown[]): unknown;
	userPermissions: RolePermission[] | null;
	setUserPermissions: (permissions: RolePermission[]) => void;
	userId: string;
}

const RoleCreateModal: FC<RoleCreateModalProps> = ({ isOpen, setIsOpen, userPermissions, userId, setUserPermissions }) => {

	const { handleErrors } = useHandleErrors();

	const [selectedPermissions, setSelectedPermissions] = useState<number[]>([]);
	const [selectAll, setSelectAll] = useState<number[]>([]);
	const [updating, setUpdating] = useState<boolean>(false);

	const [permissionsData, permissionsLoading, error] = useFetch(useCallback(async () => {
		const permissionService = new PermissionService();
		const response = await permissionService.getPermissions();
		return response.getResponseData() as PermissionsApiResponse;
	}, []));

	const updatePermissions = async () => {
		try {
			setUpdating(true);
			let response = await (await (new UserService()).editUserPermissions(userId, selectedPermissions)).getResponseData() as UserApiResponse;
			if (response.success && response.data) {
				setUserPermissions(response.data.userPermissions);
				toast.success(response.message);
			} else {
				handleErrors(response);
			}
		} catch (error: any) {
			setIsOpen(false);
			toast.error('Error al actualizar los permisos');
		} finally {
			setUpdating(false);
			setIsOpen(false);
		}
	};

	const _restorePermissions = async () => {
		handleConfirmationAlert({
			title: 'Restaurar Permisos',
			text: '¿Estás seguro de que deseas restaurar los permisos del usuario?',
			icon: 'warning',
			onConfirm: async () => {
				setUpdating(true);
				const response = await (await (new UserService()).restoreUserPermissions(userId)).getResponseData();

				if (response.success) {
					toast.success(response.message);
					setUpdating(false);
					setIsOpen(false);
				} else {
					toast.error(response.message);
					handleErrors(response)
					setUpdating(false);
				}
			}
		})
	};

	useEffect(() => {
		if (userPermissions) {
			setSelectedPermissions(userPermissions.map((permission: RolePermission) => permission.permission.id));
		}
	}, [userPermissions]);

	const getContent = () => {
		if (permissionsLoading) return (<div className="text-center"><Spinner /></div>);

		if (error) return <ErrorMessage />;

		return permissionsData?.map((group: PermissionGroup, index: number) => {
			return (
				<div className="col-lg-3 col-md-6 col-sm-6 mb-5" key={index}>
					<Accordion id={group.name} isFlush activeItemId={group.id}>
						<AccordionItem id={group.id} title={`${group.label}`}>
							<>
								<Checks
									label="Seleccionar todos"
									value="true"
									checked={selectAll.includes(group.id)}
									onChange={() => {
										const list = group.permissions.map((item: Permission) => item.id);
										if (selectAll.includes(group.id)) {
											setSelectAll(selectAll.filter((id: number) => id !== group.id));
											setSelectedPermissions(selectedPermissions.filter(item => !list.includes(item)));
										} else {
											setSelectAll([...selectAll, group.id]);
											setSelectedPermissions([...selectedPermissions.concat(list)]);
										}
									}}
								/>
							</>
							{group.permissions.map((permission: Permission, index: number) => {
								return (
									<div key={index}>
										<Checks
											label={(
												<>
													<Tooltips title={`${permission.action}: ${permission.description}`}>
														<Icon icon="Info" color="primary" ></Icon>
													</Tooltips>
													{permission.label}
												</>
											)}
											value={permission.id}
											checked={selectedPermissions.includes(permission.id)}
											onChange={() => {
												selectedPermissions.includes(permission.id) ?
													setSelectedPermissions(selectedPermissions.filter((id: number) => id !== permission.id)) :
													setSelectedPermissions([...selectedPermissions, permission.id])
											}}
										/>
									</div>
								);
							})}
						</AccordionItem>
					</Accordion>
				</div>
			);
		});
	};

	return (
		<Modal isOpen={isOpen} setIsOpen={setIsOpen} size='xl' titleId='Nuevo Rol'>
			<ModalHeader setIsOpen={setIsOpen} className='p-4'>
				<ModalTitle id='new_role'>
					Editar permisos
					<Tooltips title="Restaura todos los permisos del usuario estableciendo los del ROL asignado por defecto">
						<Button className="ms-2" icon="Autorenew" color="info" isLight onClick={_restorePermissions}>Restaurar Permisos</Button>
					</Tooltips>

				</ModalTitle>
			</ModalHeader>
			<ModalBody className='px-4'>
				<div className="row">
					{getContent()}
				</div>
			</ModalBody>
			<ModalFooter className='px-4 pb-4'>
				<Button isDisable={updating} icon={updating ? '' : 'Save'} color='primary' onClick={updatePermissions}>
					{updating ? (<Fragment> <Spinner isSmall /> Actualizando... </Fragment>) : 'Actualizar'}
				</Button>
			</ModalFooter>
		</Modal>
	)
}

export default RoleCreateModal;