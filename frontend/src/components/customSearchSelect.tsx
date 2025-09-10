import React, { useEffect, useRef } from 'react'
import Select, { ActionMeta, GroupBase, MultiValue, OptionsOrGroups, SingleValue } from 'react-select';

type Props = {
    id: string,
    search?: string,
    getOptions?: () => void,
    className?: string,
    isClearable?: boolean,
    options?: OptionsOrGroups<unknown, GroupBase<unknown>>,
    isMulti?: boolean,
    isRequired?: boolean,
    onChange?: (selected: MultiValue<unknown>) => void,
    onChangeSingle?: (selected: SingleValue<unknown>) => void,
    onInputChange?: (inputValue: string) => void,
    value?: OptionsOrGroups<any, any> | null | unknown,
    defaultValue?: OptionsOrGroups<any, any> | null | unknown,
    isDisabled?: boolean,
}

const CustomSearchSelect: React.FC<Props> = ({ options, onChange, isMulti, isRequired, onChangeSingle, defaultValue, value, onInputChange, isDisabled, id, className, isClearable, search, getOptions }) => {

    const timeoutRef = useRef<any>(null);

    const customStyles = {
        control: (base: any, state: any) => ({
            ...base,
            borderRadius: '10px',
            boxShadow: state.isFocused ? '0 0 0 3px rgba(0, 0, 0, 0.3)' : '0 -1.5px 0 1px rgba(0,0,0, 0.07) !important',
            border: '0px !important',
            backgroundColor: isDisabled ? '#e9ecef' : '#f8f9fa',
        }),
        option: (provided: any, state: any) => ({
            ...provided,
            backgroundColor: state.isFocused ? '#000000' : 'white',
            color: state.isFocused ? 'white' : 'black',
            '&:hover': {
                backgroundColor: '#3D3D3D',
                color: 'white',
                borderColor: '#000000 !important'
            }
        })
    };

    const onSelectChange = (newValue: MultiValue<unknown>, actionMeta: ActionMeta<unknown>) => {
        if (onChange !== undefined) {
            onChange(newValue);
        }
    };
    const onSelectChangeSingle = (newValue: SingleValue<unknown>, actionMeta: ActionMeta<unknown>) => {
        if (onChangeSingle !== undefined) {
            onChangeSingle(newValue);
        }
    };
    const onInputValueChange = (inputValue: string) => {
        if (onInputChange !== undefined) {
            onInputChange(inputValue);
        }
    };

    useEffect(() => {
        if (search && search !== '' && search.length > 1) {
            clearTimeout(timeoutRef.current)
            timeoutRef.current = setTimeout(() => {
                getOptions && getOptions();
            }, 500);
            return () => { clearTimeout(timeoutRef.current) }
        }
    }, [search]);

    return (
        <>
            {
                isMulti === true
                    ? (
                        <Select
                            id={id}
                            isSearchable={true}
                            isMulti={isMulti}
                            isClearable={isClearable ? isClearable : false}
                            isDisabled={isDisabled}
                            options={options}
                            value={value}
                            defaultValue={defaultValue}
                            required={isRequired}
                            onChange={onSelectChange}
                            onInputChange={onInputValueChange}
                            placeholder='Empieza a escribir para mostrar opciones ...'
                            noOptionsMessage={() => 'No se ha encontrado ningún resultado'}
                            className={className}
                            styles={customStyles}
                        />
                    ) : (
                        <Select
                            id={id}
                            isSearchable={true}
                            isMulti={isMulti}
                            isClearable={isClearable ? isClearable : false}
                            isDisabled={isDisabled}
                            options={options}
                            value={value}
                            defaultValue={defaultValue}
                            required={isRequired}
                            onChange={onSelectChangeSingle}
                            onInputChange={onInputValueChange}
                            placeholder='Empieza a escribir para mostrar opciones ...'
                            noOptionsMessage={() => 'No se ha encontrado ningún resultado'}
                            className={className}
                            styles={customStyles}
                        />
                    )
            }
        </>
    )
};
export default CustomSearchSelect;