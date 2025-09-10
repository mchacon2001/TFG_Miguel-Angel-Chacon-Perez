import PageWrapper from '../../layout/PageWrapper/PageWrapper';
import Page from '../../layout/Page/Page';
import ErrorImg from '../../assets/404.png';
import Button from '../../components/bootstrap/Button';

const Page404 = () => {
	return (
		<PageWrapper className="page-404">
			<Page> 			
				<div className='row d-flex align-items-center h-100'>
					<div className='col-12 d-flex flex-column justify-content-center align-items-center'>
						<div className='text-white fw-bold' style={{ fontSize: 'calc(1.5rem + 1.5vw)' }}>
							No se ha encontrado la página
						</div>
					</div>
					<div className='col-12 d-flex align-items-baseline justify-content-center'>
						<img src={ErrorImg} alt='Humans' style={{ height: '30vh' }} />
					</div>
					<div className='col-12 d-flex flex-column justify-content-center align-items-center'>
						<Button
							className='px-5 py-3'
							color='primary'
							isLight
							icon='HolidayVillage'
							tag='a'
							href='/'
						>
							Inicio
						</Button>
					</div>
				</div>
			</Page>
		</PageWrapper>
	);
};

export default Page404;