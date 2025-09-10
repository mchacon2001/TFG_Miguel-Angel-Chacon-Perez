import { useState, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import classNames from 'classnames';
import useDarkMode from '../../hooks/useDarkMode';
import Collapse from '../../components/bootstrap/Collapse';
import { NavigationLine } from '../Navigation/Navigation';
import Icon from '../../components/icon/Icon';
import useNavigationItemHandle from '../../hooks/useNavigationItemHandle';
import AsyncImg from '../../components/AsyncImg';
import useIsMobile from '../../hooks/useIsMobile';
import ThemeContext from '../../contexts/themeContext'; 
import { useDispatch, useSelector } from 'react-redux';
import { AppDispatch, RootState } from '../../redux/store';
import { logout } from '../../redux/authSlice';
import { getUserRoles } from "../../helpers/helpers";
import { usersMenu } from '../../menu';

const User = () => {
	const navigate = useNavigate();
	const dispatch = useDispatch<AppDispatch>();
	const handleItem = useNavigationItemHandle();
	const { darkModeStatus, setDarkModeStatus } = useDarkMode();

	const [collapseStatus, setCollapseStatus] = useState<boolean>(false);

	const user = useSelector((state: RootState) => state.auth.user);
	const isMobile = useIsMobile(); 
	const { asideStatus } = useContext(ThemeContext); 

	return (
		<>
			<div
				className={classNames('user', { open: collapseStatus })}
				role='presentation'
				onClick={() => setCollapseStatus(!collapseStatus)}
			>
				<div className='user-avatar'>
					<AsyncImg id={user?.profilePictureId ? user.profilePictureId : null} width='128' height='128' />
				</div>

				{(!isMobile || asideStatus) && (
					<div className='user-info'>
						<div className='user-name'>
							{user?.name ? user.name : 'Usuario'}
						</div>
						<div className='user-role'>
							{getUserRoles(user)?.map((role: string) => (
								<span key={'profile-role' + role}>{role}</span>
							))}
						</div>
					</div>
				)}
			</div>

			<Collapse isOpen={collapseStatus} className='user-menu'>
				<nav aria-label='aside-bottom-user-menu'>
					<div className='navigation'>
						<div
							role='presentation'
							className='navigation-item cursor-pointer'
							onClick={() => navigate(`${usersMenu.users.path}/${user?.id}/profile`)}>
							<span className='navigation-link navigation-link-pill'>
								<span className='navigation-link-info'>
									<Icon icon='AccountBox' className='navigation-icon' />
									<span className='navigation-text'>Perfil</span>
								</span>
							</span>
						</div>
					</div>
				</nav>
				<NavigationLine />
				<nav aria-label='aside-bottom-user-menu-2'>
					<div className='navigation'>
						<div
							role='presentation'
							className='navigation-item cursor-pointer'
							onClick={() => { dispatch(logout()) }}>
							<span className='navigation-link navigation-link-pill'>
								<span className='navigation-link-info'>
									<Icon icon='Logout' className='navigation-icon' />
									<span className='navigation-text'>Cerrar sesión</span>
								</span>
							</span>
						</div>
					</div>
				</nav>
			</Collapse>
		</>
	);
};

export default User;
