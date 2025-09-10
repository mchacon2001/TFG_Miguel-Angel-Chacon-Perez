import { CSSProperties, FC } from "react";
import Tooltips from "../Tooltips";
import Icon from "../../icon/Icon";
import { TIconsSize } from "../../../type/icons-type";

interface FormLabelProps {
    label: string;
    cols?: number;
    className?: string;
    style?: CSSProperties;
    required?: boolean;
    tooltip?: boolean;
    tooltipLabel?: string;
    icon?: string;
    size?: TIconsSize;
    color?: string;
}

const FormLabel: FC<FormLabelProps> = ({ label, cols, className, required, style, tooltip, tooltipLabel, icon, size, color }) => {
    return (
        <span className={`form-label col-md-${cols} ${className} pe-0`} style={style}>
            {label}
            {required && <span style={{ color: 'red' }}> *</span>}
            {tooltip && (
                <Tooltips placement="top" title={tooltipLabel}>
                    <Icon icon={icon} className="ms-2" size={size} color={color} />
                </Tooltips>
            )}
        </span>
    )
}

export default FormLabel;