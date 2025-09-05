const useCodes = () => {

    const changeImgKC = (parentClassName: string, imgClassName: string) => {
        const img = document.createElement('img');
        img.src = '/konami-img-2.png';
        img.alt = 'Logo de BrainyGym';
        img.height = 150;

        document.body.getElementsByClassName(imgClassName)[0].classList.add('d-none');
        document.body.getElementsByClassName(parentClassName)[0].appendChild(img);
    };

    const changeTextKC = (parentClassName: string, textClassName: string) => {
        const text = document.createElement('div');
        text.innerHTML = '¡Bienvenido a BrainyGym!';

        document.body.getElementsByClassName(textClassName)[0].classList.add('d-none');
        document.body.getElementsByClassName(parentClassName)[0].appendChild(text);
    }

    return { changeImgKC, changeTextKC };
}

export default useCodes;