import { useEffect, useState } from 'react';
import { hasNotch } from '../helpers/helpers';
export default function useDeviceScreen() {
	
    const isClient = typeof window === 'object';

    function getProperties() {
        return {
            width: isClient ? window.innerWidth : 0, 
            height: isClient ? window.innerHeight : 0, 
            screenWidth: isClient ? window.screen.width : 0, 
            screenHeight: isClient ? window.screen.height : 0,
            portrait: isClient ? window.matchMedia('(orientation: portrait)').matches : false, 
            landscape: isClient ? window.matchMedia('(orientation: landscape)').matches : false, 
            notch: hasNotch(),
        };
    }

    const [deviceScreen, setDeviceScreen] = useState(getProperties);

    useEffect(() => {
        if (!isClient) {
            return;
        }

        function handleResize() {
            setDeviceScreen(getProperties());
        }

        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    return deviceScreen;
}
