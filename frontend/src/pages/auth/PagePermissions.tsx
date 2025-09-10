import PageWrapper from '../../layout/PageWrapper/PageWrapper';
import Page from '../../layout/Page/Page';
import Errors from '../../assets/img/error_message.png';
import Button from '../../components/bootstrap/Button';

const PagePermissions = () => {
	return (
		<PageWrapper>
			<Page>
				<div className='row d-flex align-items-center h-100'>
					<div className='col-12 d-flex flex-column justify-content-center align-items-center'>
						<div
							className='text-primary fw-bold'
							style={{ fontSize: 'calc(2rem + 3vw)' }}>
							Autenticación
						</div>
						<div
							className='text-dark fw-bold'
							style={{ fontSize: 'calc(1rem + 1.5vw)' }}>
							Permisos no encontrados.
						</div>
					</div>
					<div className='col-12 d-flex align-items-baseline justify-content-center'>
						<img
							srcSet={Errors}
							src={Errors}
							alt='Humans'
							style={{ height: '50vh' }}
						/>
					</div>
					<div className='col-12 d-flex flex-column justify-content-center align-items-center'>
						<Button
							className='px-5 py-3'
							color='primary'
							isLight
							icon='HolidayVillage'
							tag='a'
							href='/'>
							Inicio
						</Button>
					</div>
				</div>
			</Page>
		</PageWrapper>
	);
};

export default PagePermissions;
