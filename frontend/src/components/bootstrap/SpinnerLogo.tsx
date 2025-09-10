import React from 'react';

interface SpinnerLogoProps {
    loading?: boolean;
    height?: string;
}

const SpinnerLogo: React.FC<SpinnerLogoProps> = ({ loading, height }) => {
    return (
        loading
            ? <div className='d-flex justify-content-center align-items-center' style={{ height: height || '100vh' }}>
                <div className="rotate">
                <img src={`${process.env.PUBLIC_URL}/logo1920.png`} style={{ width: '100px' }} />
                </div>
            </div>
            : null
    );
};

export default SpinnerLogo;

export const Loader = ({ height }: SpinnerLogoProps) => {
    return (
        <div className='d-flex justify-content-center align-items-center' style={{ height: height || '80vh' }}>
            <div className="rotate">
            <img src={`${process.env.PUBLIC_URL}/logo1920.png`} style={{ width: '100px' }} />
            </div>
        </div>
    );
};

