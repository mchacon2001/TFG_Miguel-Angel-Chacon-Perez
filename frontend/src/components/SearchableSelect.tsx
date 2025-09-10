import React from 'react';
import SelectReact from 'react-select';

type SelectProps = {
    name: string;
    isSearchable?: boolean;
    isMulti?: boolean;
    options?: any;
    onChange?: any;
    handle?: any;
    value?: any;
    defaultValue?: any;
    placeholder?: string;
    classname?: string;
    onBlur?: any;
    isClearable?: boolean;
    isDisabled?: boolean;
};

const SearchableSelect: React.FC<SelectProps> = ({ name, isSearchable, isMulti, options, onChange, handle, value, defaultValue, placeholder, classname, onBlur, isClearable, isDisabled }) => {

    return (
        <SelectReact
            name={name}
            id={name}
            isSearchable={isSearchable}
            isMulti={isMulti}
            isClearable={isClearable}
            options={options}
            onChange={onChange ? ((selectedOption: any) => { onChange(selectedOption) }) : handle}
            value={value}
            defaultValue={defaultValue}
            placeholder={`${placeholder || ''} ...`}
            noOptionsMessage={() => 'No se ha encontrado ningún resultado'}
            className={classname}
            onBlur={onBlur}
            styles={{
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
                }),
            }}
            isDisabled={isDisabled}
        />
    );
};

export default SearchableSelect;