import { useEffect, useRef } from 'react';

export default function useEventListener(eventName: string, handler: unknown, element = window) {
	const savedHandler = useRef();

	useEffect(() => {
		// @ts-ignore
		savedHandler.current = handler;
	}, [handler]);
	useEffect(
		() => {
			const isSupported = element && element.addEventListener;
			if (!isSupported) return;
			// @ts-ignore
			const eventListener = (event: any) => savedHandler.current(event);
			element.addEventListener(eventName, eventListener);
			return () => {
				element.removeEventListener(eventName, eventListener);
			};
		},
		[eventName, element],
	);
}
