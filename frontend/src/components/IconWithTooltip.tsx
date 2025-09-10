import { FC } from 'react';
import Tooltips from './bootstrap/Tooltips';
import Icon from './icon/Icon';
import { TIconsSize } from '../type/icons-type';

interface IconProps {
    icon: string;
    label: string;
    size?: TIconsSize;
    color?: string;
}

const IconWithTooltip: FC<IconProps> = ({ icon, label, size, color }) => {
    return (
        <Tooltips placement="top" title={label}>
            <Icon icon={icon} className="ms-2" size={size} color={color} />
        </Tooltips>
    );
};

IconWithTooltip.defaultProps = {
    size: 'lg',
    color: 'black',
};

export default IconWithTooltip;