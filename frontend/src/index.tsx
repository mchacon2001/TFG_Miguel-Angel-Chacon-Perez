import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router } from 'react-router-dom';
import './styles/styles.scss';
import App from './App/App';
import { ThemeContextProvider } from './contexts/themeContext';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import store from './redux/store';
import { Provider } from 'react-redux';
import { LoaderProvider } from './components/loader/LoaderProvider';
import { registerServiceWorker } from './sw';


const client = new QueryClient();

const children = (
	<ThemeContextProvider>
		<Provider store={store}>
			<LoaderProvider>
				<QueryClientProvider client={client}>
					<Router>
						<React.StrictMode>
							<App />
						</React.StrictMode>
					</Router>
				</QueryClientProvider>
			</LoaderProvider>
		</Provider>
	</ThemeContextProvider>
);


	registerServiceWorker().then(() => {

	const container = document.getElementById('root');

	createRoot(container as Element).render(children);

});
