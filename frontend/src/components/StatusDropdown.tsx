import { FC, Fragment, useState } from "react";
import Button from "./bootstrap/Button"
import Dropdown, { DropdownToggle, DropdownMenu, DropdownItem } from "./bootstrap/Dropdown"
import Spinner from "./bootstrap/Spinner";

interface StatusDropdownProps {
    itemId: string;
    status: boolean;
    change: (id: string, status: boolean, toggleStatus: Function) => void;
    disabled?: boolean;
    additionalInfo?: string;
}

const StatusDropdown: FC<StatusDropdownProps> = ({itemId, status, change, disabled=false, additionalInfo}) => {

    const [isActive, setIsActive] = useState<boolean>(status);

    const toggleState = (newStatus: boolean) => {
        change(itemId, newStatus, setIsActive);
    }
   
    return (
        <Dropdown>
            <DropdownToggle hasIcon={false}>
                <Button isLink isDisable={disabled} color={isActive ? 'success' : 'danger'} icon='Circle' iconColor={isActive ? 'success' : 'danger'} className='text-nowrap'> 
                    {disabled ? <Spinner isSmall/> : <Fragment> {isActive ? 'Activado' : 'Desactivado'}  </Fragment>}
                    {additionalInfo && <Fragment> <br/> <small className='text-muted'> {additionalInfo} </small> </Fragment>}
                </Button>
            </DropdownToggle>
            <DropdownMenu>
                    <DropdownItem>
                        <Button isLink onClick={()=>toggleState(!isActive)} color={isActive ? 'danger' : 'success'} icon='Circle' className='text-nowrap'> {isActive ? 'Desactivado' : 'Activado'} </Button>
                    </DropdownItem>
            </DropdownMenu>
        </Dropdown>
    )
}

export default StatusDropdown;

