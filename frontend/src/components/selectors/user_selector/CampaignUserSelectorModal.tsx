import { useCallback, useEffect, useState } from "react";
import Modal, { ModalBody, ModalFooter, ModalHeader, ModalTitle } from "../../bootstrap/Modal";
import Button from "../../bootstrap/Button";
import useFetch from "../../../hooks/useFetch";
import FormGroup from "../../bootstrap/forms/FormGroup";
import { User } from "../../../type/user-type";
import { UserService } from "../../../services/users/userService";
import Select, { ActionMeta } from 'react-select';
import { ReactSelectStyles } from '../../../utils/styles';

type CampaignUserSelectorModalProps = {
    isOpen: boolean;
    setIsOpen: (value: boolean) => void;
    onClose: Function;
    defaultSelected?: string | null;
    isMulti?: boolean;
    discardSelected?: boolean;
    isRequired?: boolean;
}

const CampaignUserSelectorModal: React.FC<CampaignUserSelectorModalProps> = ({ isOpen, setIsOpen, onClose, defaultSelected, isMulti, discardSelected, isRequired}) => {
    
    const [selectedOption, setSelectedOption] = useState<string[]|string|null>(null);

    const [data] = useFetch(
        useCallback(async () => {
            const service = new UserService();
            const response = await service.getUsers({active : true});
            return response.getResponseData();
        }, [])
    )

    const _getOptions = (): any[] => {
        let options: any = [];
        if (data) {
            data.users.forEach((item: User) => {
                if(discardSelected && defaultSelected && defaultSelected.length > 0) {
                    let found = defaultSelected == item.id;
                    if(found) {
                        return;
                    }
                }
                options.push({ value: item.id, label: item.name });
            })
        }
        return options;
    }

    const _getDefaultSelectedValues = (): any => {
        let option = null;
        if (selectedOption && data) {
            let optionData = data.users.filter((item: User) => selectedOption.includes(item.id));
            option = optionData.map((item: User) => ({ value: item.id, label: item.name }));
        }
        return option;
    }

    const _handleChange = (newValue: any, actionMeta: ActionMeta<any>) => {
        if (Array.isArray(newValue)) {
            let selected = newValue.map((option: any) => option.value);
            setSelectedOption(selected);
        } else {
            setSelectedOption(newValue.value);
        }
    }

    const _notifyChange = () => {
        setIsOpen(false);
        onClose(selectedOption);
    }

    return (
        <>
            <Modal isOpen={isOpen} setIsOpen={setIsOpen} onClose={_notifyChange} size={'lg'}>
                <ModalHeader setIsOpen={setIsOpen}>
                    <ModalTitle id="modal-allowed-statuses">Añadir us</ModalTitle>
                </ModalHeader>
                <ModalBody>
                <FormGroup label='Usuarios' requiredInputLabel={isRequired} color={'primary'}>
                    <Select styles={ReactSelectStyles} placeholder="Elige un usuario"
                        name={"Ususarios"} isMulti={isMulti} options={_getOptions()} value={_getDefaultSelectedValues()} onChange={_handleChange} />
                </FormGroup>
                </ModalBody>
                <ModalFooter>
                    <Button color="primary" onClick={_notifyChange}>Guardar</Button>
                </ModalFooter>
            </Modal>
        </>
    );
};


export default CampaignUserSelectorModal;