import { useFormik } from "formik";
import { FC, Fragment, useCallback, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { toast } from "react-toastify";
import Accordion, { AccordionItem } from "../../components/bootstrap/Accordion";
import Button from "../../components/bootstrap/Button";
import { CardBody, CardFooter, CardFooterRight } from "../../components/bootstrap/Card";
import Checks from "../../components/bootstrap/forms/Checks";
import FormGroup from "../../components/bootstrap/forms/FormGroup";
import Input from "../../components/bootstrap/forms/Input";
import Spinner from "../../components/bootstrap/Spinner";
import ErrorMessage from "../../components/ErrorMessage";
import useFetch from "../../hooks/useFetch";
import { PermissionService } from "../../services/auth/permissionService";
import { RoleService } from "../../services/auth/roleService";
import { Permission, PermissionGroup, RolesApiResponse } from "../../type/role-type";

interface CreateFormProps {
    isLoading: boolean;
    submit: (values: RoleForm) => void;
    roleData?: any;
}

export interface RoleForm {
    name: string;
    description: string;
    permissions: RolePermissions;
}

export interface RolePermissions {
    users: string[];
    roles: string[];
}

const roleInitialValues: RoleForm = {
    name: '',
    description: '',
    permissions: {
        users: [],
        roles: [],
    },
}

const RoleForm: FC<CreateFormProps> = ({ isLoading, roleData }) => {

    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();
    const [selectedPermissions, setSelectedPermissions] = useState<number[]>([]);
    const [selectAll, setSelectAll] = useState<number[]>([]);

    const [permissionData, loadingPermission, errorPermission, fetchPermissionData] = useFetch(useCallback(async () => {
        const permissionService = new PermissionService();
        const response = await permissionService.getPermissions();

        const allPermissionsData = response.getResponseData().data;

        const defaultSelectedPermissions = allPermissionsData.reduce((acc: any, group: PermissionGroup) => {
            let permissionIdsOfGroup: number[] = [];
            group.permissions.forEach((permission: Permission) => {
                if (roleData?.permissions.find((objeto: any) => objeto.permission.id === permission.id)) {
                    permissionIdsOfGroup.push(permission.id);
                }
            });
            return [...acc, ...permissionIdsOfGroup];
        }, []);

        setSelectedPermissions(defaultSelectedPermissions);

        return response.getResponseData() as RolesApiResponse;

    }, []));

    const showPermissions = () => {
        if (loadingPermission) return (<div className="text-center">{" "}<Spinner />{" "}</div>);

        if (errorPermission) return <ErrorMessage error={errorPermission} />;

        if (permissionData) {
            return (
                <div className="row w-75">
                    {permissionData?.map((group: PermissionGroup, index: number) => {
                        return (
                            <div className="col-lg-3 col-md-6 col-sm-6 mt-5" key={index}>
                                <Accordion id={group.name} isFlush activeItemId={group.id}>
                                    <AccordionItem id={group.id} title={group.label}>
                                        <Fragment>
                                            <Checks
                                                label="Seleccionar todos"
                                                value="all"
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
                                        </Fragment>

                                        {group.permissions.map((permission: Permission, index: number) => {
                                            return (
                                                <div key={permission.id}>
                                                    <Checks
                                                        label={permission.label}
                                                        value={permission.id}
                                                        name={`permissions[]`}
                                                        checked={selectedPermissions.includes(
                                                            permission.id
                                                        )}
                                                        onChange={() => {
                                                            selectedPermissions.includes(permission.id)
                                                                ? setSelectedPermissions(
                                                                    selectedPermissions.filter((id: number) => id !== permission.id)
                                                                )
                                                                : setSelectedPermissions([...selectedPermissions, permission.id]);
                                                        }}
                                                    />
                                                </div>
                                            );
                                        },
                                        )}
                                    </AccordionItem>
                                </Accordion>
                            </div>

                        );
                    })}
                </div>
            )
        }
    };

    const handleEditRole = async (values: any) => {
        if (id) values.roleId = parseInt(id);
        values.permissions = selectedPermissions;
        if (values && id) {
            try {
                const response = await (await new RoleService().editRole(values)).getResponseData();

                if (response.success) {
                    navigate('/roles');
                    setTimeout(() => {
                        toast.success('Rol editado correctamente');
                    }, 500);
                } else {
                    throw new Error(response.message);
                }
            } catch (e: any) {
                console.error(e);
                toast.error(e.message || 'Error al editar el rol');
            }
        }
    };

    const formik = useFormik({
        initialValues: roleData ? roleData : roleInitialValues,
        onSubmit: handleEditRole,
    });

    return (
        <Fragment>
            <form onSubmit={formik.handleSubmit}>
                <CardBody isScrollable={false}>
                    <div className="row">
                        <div /* className='col-md-6' */>
                            <CardBody className="d-flex flex-column">
                                <div className="row d-flex justify-content-around ">
                                    <FormGroup label='Nombre' className='col-md-4'>
                                        <Input id='name' onChange={formik.handleChange} value={formik.values.name} />
                                    </FormGroup>
                                    <FormGroup label='Descripción' className='col-md-6'>
                                        <Input id='description' onChange={formik.handleChange} value={formik.values.description} />
                                    </FormGroup>

                                </div>
                                <div className="row mt-5 d-flex justify-content-center">
                                    {showPermissions()}
                                </div>
                            </CardBody>
                        </div>
                    </div>

                </CardBody>
                <CardFooter>
                    <CardFooterRight>
                        <Button type="submit" size='lg' color='primary'>
                            {isLoading ? <Spinner /> : `Guardar cambios`}
                        </Button>
                    </CardFooterRight>
                </CardFooter>
            </form>
        </Fragment>
    )
}

export default RoleForm;