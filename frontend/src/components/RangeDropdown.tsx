import React from 'react';
import FormGroup from './bootstrap/forms/FormGroup';
import Dropdown, { DropdownItem, DropdownMenu, DropdownToggle } from './bootstrap/Dropdown';
import Button from './bootstrap/Button';

interface RangeDropdownProps {
    filters: any;
    updateFilters: (filters: any) => void;
    className?: string;
}

const RangeDropdown: React.FC<RangeDropdownProps> = ({ filters, updateFilters, className }) => {
    return (
        <FormGroup label='Rango de fechas:' className={className}>
            <Dropdown>
                <DropdownToggle>
                    <Button color='primary' isLight icon='CalendarToday'>
                        {filters.filter_filters?.date?.mode === "months" ? "Mes" : filters.filter_filters?.date?.mode === "weeks" ? "Semana" : "Día"}
                    </Button>
                </DropdownToggle>
                <DropdownMenu isAlignmentEnd>
                    <DropdownItem>
                        <Button color='link' icon='calendar_view_month' onClick={() => updateFilters({ date: { ...filters.filter_filters?.date, mode: "months" } })}>Mes</Button>
                    </DropdownItem>
                    <DropdownItem>
                        <Button color='link' icon='calendar_view_week' onClick={() => updateFilters({ date: { ...filters.filter_filters?.date, mode: "weeks" } })}>Semana</Button>
                    </DropdownItem>
                    <DropdownItem>
                        <Button color='link' icon='calendar_view_day' onClick={() => updateFilters({ date: { ...filters.filter_filters?.date, mode: "days" } })}>Día</Button>
                    </DropdownItem>
                </DropdownMenu>
            </Dropdown>
        </FormGroup>
    );
};

export default RangeDropdown;