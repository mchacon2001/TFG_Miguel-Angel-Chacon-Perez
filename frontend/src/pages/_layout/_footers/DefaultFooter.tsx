import Icon from '../../../components/icon/Icon';
import Footer from '../../../layout/Footer/Footer';

const DefaultFooter = () => {
	return (
		<Footer>
			<div className='container-fluid'>
				<div className='row'>
					<div className='col'>
						<code className='ps-3'>BrainyGym &copy;</code>
					</div>
					<div className='col-auto'>
						<p className='ps-3'>Handcrafted and made with <Icon icon='Favorite' color='danger' size='lg' /> by <code> i92chpem </code> </p>
					</div>
				</div>
			</div>
		</Footer>
	);
};

export default DefaultFooter;
