import { FC, useState, useRef, useEffect } from 'react';
import { DocumentService } from '../services/documents/documentService';
import { AxiosResponse } from 'axios';
import Spinner from './bootstrap/Spinner';
import Avatar from './Avatar';
import DefaultUserImg from "..//assets/img/defaults/default-user-image.png";
import Icon from './icon/Icon';
interface IAsyncImg {
    id: string | null,
    isBackground?: boolean,
    height?: string,
    width?: string,
    styles?: string,
    defaultAvatarSize?: number,
    closeBtn?: boolean,
    onClick?: () => void
}
const AsyncImg: FC<IAsyncImg> = ({ id, styles = '', isBackground = false, defaultAvatarSize, closeBtn = false, onClick, ...props }) => {
    const [imgSrc, setImgSrc] = useState<string>('');
    const divRef = useRef<HTMLDivElement | null>(null);
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<boolean>(false);
    useEffect(() => {
        const fetchData = async (docId: string) => {
            try {
                setLoading(true);
                const response = (await (new DocumentService()).renderDocument(docId)).getResponse() as AxiosResponse;
                if (response.status === 200 && response.data) {
                    let file = new Blob([response.data], { type: 'image/jpeg' });
                    let stream = URL.createObjectURL(file);
                    setImgSrc(stream);
                }
            } catch (error) {
                setError(true);
            } finally {
                setLoading(false);
            }
        };
        id ? fetchData(id) : setError(true);
    }, [id]);
    if (loading ) return <Spinner isSmall />
    if (loading) return <div className='text-center'><Spinner isSmall /></div>
    if (error) return (
        // si tiene closeBtn, se agrega un icono pequeño de cerrar en la esquina superior derecha de la imagen
        <div className="position-relative">
            <Avatar src={DefaultUserImg} size={defaultAvatarSize}/>
            {closeBtn && <div className="position-absolute top-0 end-0">
                <Icon icon='Close' className='cursor-pointer me-1' color='dark' onClick={onClick} />
            </div>}
        </div>
    )
    if (isBackground && divRef.current) {
        divRef.current.style.backgroundImage = `url(${imgSrc})`;
        divRef.current.style.backgroundPosition = 'center';
        divRef.current.style.backgroundSize = 'cover';
        return (
            <div ref={divRef} className={styles} style={{ ...props }}></div>
        )
    }
    return (
        // si tiene closeBtn, se agrega un icono pequeño de cerrar en la esquina superior derecha de la imagen
        <div className="position-relative">
            <img {...props} className={styles} src={imgSrc} alt='img' style={{objectFit: 'cover'}} />
            {closeBtn && <div className="position-absolute top-0 end-0">
                <Icon icon='Close' className='cursor-pointer me-1' color='dark' onClick={onClick} />
            </div>}
        </div>
    );
}
export default AsyncImg;