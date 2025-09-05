import React, { forwardRef, ReactNode } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import Icon from '../icon/Icon';
import Popovers from '../bootstrap/Popovers';
import { TColor } from '../../type/color-type';

interface ITimelineItemProps {
	children: ReactNode;
	className?: string;
	color?: TColor | string;
	style?: React.CSSProperties;
	label: string;
	noTruncate?: boolean;
}
export const TimelineItem = forwardRef<HTMLDivElement, ITimelineItemProps>(
	({ className, color, style, label, children, noTruncate, ...props }, ref) => {
		return (
			// eslint-disable-next-line react/jsx-props-no-spreading
			<div ref={ref} className={classNames('timeline-item', className)} {...props}>
				<div className={`${noTruncate ? "timeline-label-extended" : "timeline-label text-truncate"} d-inline-block fw-bold`}>
					<Popovers desc={label} trigger='hover'>
						<span>{label}</span>
					</Popovers>
				</div>
				<div className='timeline-badge'>
					<Icon icon='Circle' color={color} style={style} size='lg' />
				</div>
				<div className='timeline-content ps-3'>{children}</div>
			</div>
		);
	},
);
TimelineItem.displayName = 'TimelineItem';
TimelineItem.propTypes = {
	className: PropTypes.string,
	color: PropTypes.string,
	style: PropTypes.objectOf(PropTypes.any),
	label: PropTypes.string.isRequired,
};
TimelineItem.defaultProps = {
	className: undefined,
	color: 'primary',
	style: undefined,
};

interface ITimelineProps {
	children: ReactNode;
	className?: string;
	noTruncate?: boolean;
}
const Timeline = forwardRef<HTMLDivElement, ITimelineProps>(
	({ className,noTruncate, children, ...props }, ref) => {
		return (
			// eslint-disable-next-line react/jsx-props-no-spreading
			<div ref={ref} className={classNames(noTruncate ? 'timeline-extended' : 'timeline', className)} {...props}>
				{children}
			</div>
		);
	},
);
Timeline.displayName = 'Timeline';
Timeline.propTypes = {
	className: PropTypes.string,
	noTruncate: PropTypes.bool,
};
Timeline.defaultProps = {
	className: undefined,
};

export default Timeline;
