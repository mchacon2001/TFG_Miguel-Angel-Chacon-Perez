import { FC } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import Icon from '../../components/icon/Icon';
import useIsMobile from '../../hooks/useIsMobile';

interface IBrandProps {
	asideStatus: boolean;
	setAsideStatus(...args: unknown[]): unknown;
}

const Brand: FC<IBrandProps> = ({ asideStatus, setAsideStatus }) => {
	const isMobile = useIsMobile();

	return (
		<div className='brand mt-3'>
			{!(isMobile && !asideStatus) && (
				<div className='brand-logo overflow-visible'>
					<h1 className='brand-title align-items-center'>
						<Link to='/' aria-label='Logo'>
							<img src='/brainygym_full_logo.png' height={60} alt='BrainyGym Logo' />
						</Link>
					</h1>
				</div>
			)}
			<button
				type='button'
				className='btn brand-aside-toggle'
				aria-label='Toggle Aside'
				onClick={() => setAsideStatus(!asideStatus)}>
				<Icon icon='FirstPage' className='brand-aside-toggle-close' />
				<Icon icon='LastPage' className='brand-aside-toggle-open' />
			</button>
		</div>
	);
};

Brand.propTypes = {
	asideStatus: PropTypes.bool.isRequired,
	setAsideStatus: PropTypes.func.isRequired,
};

export default Brand;
