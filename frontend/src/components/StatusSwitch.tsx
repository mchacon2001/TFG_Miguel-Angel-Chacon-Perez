import { FC, useState } from "react";
import Checks from "./bootstrap/forms/Checks";

interface StatusSwitchProps {
    itemId: string;
    status: boolean;
    change: (id: string, status: boolean, toggleStatus: Function) => void;
}

const StatusSwitch: FC<StatusSwitchProps> = ({ itemId, status, change }) => {

    const [isActive, setIsActive] = useState<boolean>(status);

    const toggleState = (newStatus: boolean) => {
        change(itemId, newStatus, setIsActive);
    }

    return (
        <Checks label={isActive ? 'Activo' : 'Desactivado'} checked={isActive ? true : false}
            type="switch" onChange={() => toggleState(!isActive)}
        />
    )
}

export default StatusSwitch;