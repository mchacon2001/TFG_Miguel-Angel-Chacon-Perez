import React, { useEffect, useRef, useState } from 'react'
import 'react-date-range/dist/styles.css';
import 'react-date-range/dist/theme/default.css';
import { createStaticRanges, DateRangePicker, Range, RangeKeyDict } from 'react-date-range';
import moment from 'moment';

export type RangeDate = {
    startDate: string | null,
    endDate: string | null
}

type LiteralRanges = 'this month' | 'this week' | 'this year' | 'this quarter';

type Props = {
    defaultSelection?: LiteralRanges
    maxDate?: Date
    onChangeDate?: (range: RangeDate) => void
    color?: string
    active?: boolean,
    customClass?: string,
    inputError?: boolean,
    setIsOpen?: (isOpen: boolean) => void
}

const CustomDateRangePicker: React.FC<Props> = ({ defaultSelection, maxDate, onChangeDate, color, active, customClass, inputError, setIsOpen }) => {

    const [showPicker, setShowPicker] = useState(false);
    const [startDate, setStartDate] = useState<string | null>(null);
    const [endDate, setEndDate] = useState<string | null>(null);
    const inputRangeReference = useRef(null);

    const [dateRanges, setDateRanges] = useState<Range[]>([
        {
            startDate: moment().startOf('month').toDate(),
            endDate: moment().endOf('day').subtract(1, 'days').toDate(),
            color: color,
            key: 'selection',
        }
    ]);

    const defaultMinDate = moment().subtract(2, 'year').toDate();
    const defaultMaxDate = moment().toDate();

    const inputRanges: any = []

    useEffect(() => {
        initDateRangePickerEvents();
    }, [])


    // notify change date when user closes datetimepicker
    useEffect(() => {
        if (showPicker === false && endDate && startDate) {
            notifyDateChanged();
        }
    }, [showPicker])


    // Notify data changed
    const notifyDateChanged = () => {
        if (onChangeDate !== undefined) {
            onChangeDate({
                startDate: startDate,
                endDate: endDate
            })
        }
    }


    const handleRanges = (ranges: RangeKeyDict) => {
        const { selection } = ranges;

        if (selection) {
            setDateRanges([selection]);

            setStartDate(
                selection.startDate !== undefined ? moment(selection.startDate?.getTime()).format('YYYY-MM-DD') : null
            )
            setEndDate(
                selection.endDate !== undefined ? moment(selection.endDate?.getTime()).format('YYYY-MM-DD') : null
            )
        }

    }


    const selectDateByLiteralRange = (literalRange: LiteralRanges) => {
        let startDate = new Date();
        let endDate = new Date();
        if (literalRange === "this month") {
            startDate = moment().startOf('month').toDate();
            endDate = moment().endOf('day').toDate();
        }
        if (literalRange === "this week") {
            startDate = moment().startOf('week').toDate();
            endDate = moment().endOf('day').toDate();
        }
        if (literalRange === "this year") {
            startDate = moment().startOf('year').toDate();
            endDate = moment().startOf('month').toDate();
        }

        if (literalRange === "this quarter") {
            startDate = moment().startOf('quarter').toDate();
            endDate = moment().endOf('day').toDate();
        }

        setDateRanges([{
            startDate,
            endDate,
            color: color,
            key: 'selection'
        }])

        setStartDate(moment(startDate).format('YYYY-MM-DD'));
        setEndDate(moment(endDate).format('YYYY-MM-DD'));
    }


    /** 
     * Event listeners.
    */
    const initDateRangePickerEvents = () => {

        // close when click outside of daterangepicker
        window.addEventListener('click', (event) => {
            const target = event.target as HTMLElement;
            const clickInsideDateRangePicker = target.closest('.date_range_picker');
            if (clickInsideDateRangePicker === null) {
                setShowPicker(false);
                setIsOpen && setIsOpen(false);
            }
        })

        // open dropdown window when focus on input type text.
        if (inputRangeReference.current !== null) {
            const dropdownElement = inputRangeReference.current as HTMLElement;
            dropdownElement.addEventListener('click', (event) => {
                event.stopPropagation();
                setShowPicker(true);
                setIsOpen && setIsOpen(true);
            })
        }

        // if prop default selection is defined, set de default date selected.
        if (defaultSelection !== undefined) {
            selectDateByLiteralRange(defaultSelection);
        }
    }

    const inputValue = () => {
        if (startDate !== null && endDate !== null) {
            return startDate + " - " + endDate;
        }
        return "";
    }


    const isSelectedRange = (range: Range, startDateExpected: Date, endDateExpected: Date): boolean => {

        if (range.startDate !== undefined && range.endDate !== undefined) {

            let startDateSelected = new Date(range.startDate);
            let endDateSelected = new Date(range.endDate);

            if (
                startDateSelected.getTime() === startDateExpected.getTime()
                &&
                endDateSelected.getTime() === endDateExpected.getTime()
            ) {
                return true;
            }
        }

        return false;
    }


    const staticRanges = createStaticRanges([
        {
            label: 'This year',
            isSelected: (range) => {
                return isSelectedRange(
                    range,
                    moment().startOf('year').toDate(),
                    moment().endOf('day').toDate()
                );
            },
            range: () => ({
                startDate: moment().startOf('year').toDate(),
                endDate: moment().endOf('day').toDate(),
            })
        },
        {
            label: 'This month',
            isSelected: (range) => {
                return isSelectedRange(
                    range,
                    moment().startOf('month').toDate(),
                    moment().endOf('day').toDate()
                );
            },
            range: () => ({
                startDate: moment().startOf('month').toDate(),
                endDate: moment().endOf('day').toDate(),
            }),
        },
        {
            label: 'This week',
            isSelected: (range) => {
                return isSelectedRange(
                    range,
                    moment().startOf('week').toDate(),
                    moment().endOf('day').toDate()
                );
            },
            range: () => ({
                startDate: moment().startOf('week').toDate(),
                endDate: moment().endOf('day').toDate(),
            })
        },
        {
            label: 'This quarter',
            isSelected: (range) => {
                return isSelectedRange(
                    range,
                    moment().startOf('quarter').toDate(),
                    moment().endOf('day').toDate()
                );
            },
            range: () => ({
                startDate: moment().startOf('quarter').toDate(),
                endDate: moment().endOf('day').toDate(),
            })
        }
    ])


    const getShowClass = () => {
        if (showPicker === true)
            return 'd-block'
        else
            return 'd-none'
    }

    const getInputStyle = () => {

        if (active != undefined && active === true) {
            return { backgroundColor: '#477995', color: 'white' }
        } else {
            return {}
        }
    }

    const orientation = window.matchMedia("(max-width: 700px)").matches ? 'vertical' : 'horizontal'

    return (
        <>
            <input style={getInputStyle()} ref={inputRangeReference} autoComplete='off' className={`form-control form-control-solid 
                ${inputError !== undefined && inputError == true ? 'is-invalid border-danger' : ''} ${customClass !== undefined ? customClass : ''}`}
                value={inputValue()} onChange={() => { }} placeholder="Pick date rage"
            />
            <div className={'date_range_picker position-relative ' + getShowClass()} style={{ zIndex: '20', maxWidth: '100%' }} id="date_range_picker">
                <DateRangePicker
                    className='shadow-lg zindex-modal position-absolute end-0'
                    minDate={defaultMinDate}
                    weekStartsOn={1}
                    maxDate={maxDate ? maxDate : defaultMaxDate}
                    moveRangeOnFirstSelection={false}
                    months={2}
                    onChange={handleRanges}
                    staticRanges={staticRanges}
                    direction={orientation}
                    inputRanges={inputRanges}
                    ranges={dateRanges}
                />
            </div>

        </>
    )
}

CustomDateRangePicker.defaultProps = {
    color: '#477995',
    defaultSelection: 'this month'
}

export { CustomDateRangePicker };