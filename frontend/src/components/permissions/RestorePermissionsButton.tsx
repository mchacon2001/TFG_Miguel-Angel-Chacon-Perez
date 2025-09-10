import { useState } from "react";
import { UserService } from "../../services/users/userService";
import { handleConfirmationAlert } from "../../utils/ConfirmationAlert";
import { toast } from "react-toastify";
import useHandleErrors from "../../hooks/useHandleErrors";
import Tooltips from "../bootstrap/Tooltips";
import Button from "../bootstrap/Button";
import Spinner from "../bootstrap/Spinner";

type RestorePermissionsComponentProps = {
    userId: string;
    size: "sm" | "lg" | null | undefined;
}

export const RestorePermissionsComponent: React.FC<RestorePermissionsComponentProps> = ({ userId, size }) => {

    const { handleErrors } = useHandleErrors();

    const [updating, setUpdating] = useState<boolean>(false);

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
                } else {
                    handleErrors(response)
                    setUpdating(false);
                }
            }
        })
    };

    return (
        <Tooltips title="Restaura todos los permisos del usuario estableciendo los del ROL asignado por defecto">
            {updating
                ? <Spinner isSmall />
                : <Button className="ms-2" icon="Autorenew" size={size} color="info" isLight onClick={_restorePermissions}></Button>
            }
        </Tooltips>
    )
}