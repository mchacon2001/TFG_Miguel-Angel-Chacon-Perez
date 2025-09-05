import { useContext, useEffect, useLayoutEffect, useRef } from 'react';
import { ThemeProvider } from 'react-jss';
import { useFullscreen } from 'react-use';
import ThemeContext from '../contexts/themeContext';
import useDarkMode from '../hooks/useDarkMode';
import { getOS } from '../helpers/helpers';
import AppRouter from '../router';
import { PrivilegeProvider } from "../components/priviledge/PriviledgeProvider";
import { ToastContainer } from 'react-toastify';
import Tooltips from '../components/bootstrap/Tooltips';
import COLORS from '../common/data/enumColors';

const App = () => {
    getOS();
    /**
     * Dark Mode
     */
    const { themeStatus, darkModeStatus } = useDarkMode();
    const theme = {
        theme: themeStatus,
        primary: COLORS.PRIMARY.code,
        secondary: COLORS.SECONDARY.code,
        success: COLORS.SUCCESS.code,
        info: COLORS.INFO.code,
        warning: COLORS.WARNING.code,
        danger: COLORS.DANGER.code,
        dark: COLORS.DARK.code,
        light: COLORS.LIGHT.code,
    };
    useEffect(() => {
        if (darkModeStatus) {
            document.documentElement.setAttribute('theme', 'dark');
        }
        return () => {
            document.documentElement.removeAttribute('theme');
        };
    }, [darkModeStatus]);

    /**
     * Full Screen
     */
    // @ts-ignore
    const { fullScreenStatus, setFullScreenStatus } = useContext(ThemeContext);
    const ref = useRef(null);

    useFullscreen(ref, fullScreenStatus, {
        onClose: () => setFullScreenStatus(false),
    });
    /**
     * Modern Design
     */
    useLayoutEffect(() => {
        if (process.env.REACT_APP_MODERN_DESGIN === 'true') {
            document.body.classList.add('modern-design');
        } else {
            document.body.classList.remove('modern-design');
        }
    });

    return (
        <ThemeProvider theme={theme}>
            <ToastContainer />
            <PrivilegeProvider>
                <AppRouter />
            </PrivilegeProvider>
        </ThemeProvider>
    );
};

export default App;
Tooltips.defaultProps = {
	placement: 'top',
	// @ts-ignore
	flip: ['top', 'bottom'],
	delay: 0,
	isDisplayInline: false,
	className: undefined,
	modifiers: {
		name: 'example',
		enabled: false,
		phase: 'read',
		fn: () => {},
	},
	isDisableElements: false,
};