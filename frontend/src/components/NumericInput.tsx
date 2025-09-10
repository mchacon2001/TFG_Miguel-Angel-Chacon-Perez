import React, { useState, useEffect, ChangeEvent } from 'react';
import Input from './bootstrap/forms/Input';

interface NumericInputProps {
    value: number | string; // Actualizar el tipo de valor a number o string
    onChange: (newValue: number | string) => void; // Actualizar el tipo del nuevo valor a number o string
    placeholder: string;
    id ?: string;
    className ?: any; 
    required ?: boolean;
    autoComplete ?: string;
}

const NumericInput = (props: NumericInputProps) => {
    const [value, setValue] = useState(props.value || 0);

    useEffect(() => {
        setValue(props.value);
    }, [props.value]);

    const handleInputChange = (e: ChangeEvent<HTMLInputElement>) => {
        const inputValue = e.target.value;
        const numericValue = parseFloat(inputValue);
    
        if (!isNaN(numericValue)) {
            setValue(numericValue);
            props.onChange(numericValue); // Establecer el nuevo valor como number
        } else if (/^\d*\.?\d*$/.test(inputValue)) {
            setValue(inputValue);
            props.onChange(inputValue); // Establecer el nuevo valor como string
        }
    };

    const handleInputBlur = () => {
        props.onChange(value);
    };

    return (
        <Input
            id={props.id}
            type="number"
            required={props.required}
            className={props.className}
            value={value !== null ? value.toString() : ''}
            onChange={handleInputChange}
            onBlur={handleInputBlur}
            placeholder={props.placeholder}
            autoComplete={props.autoComplete}
        />
    );
}

export default NumericInput;