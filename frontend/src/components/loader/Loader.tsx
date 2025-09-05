import Modal, { ModalBody, ModalHeader } from "../bootstrap/Modal"
import loaderAnimation from './loader.json'
import Lottie from "lottie-react"
import { CardTitle } from "../bootstrap/Card"
import './Loader.css'

type LoaderProps = {
    title: string,
    content?: React.ReactElement,
    isOpen: boolean
}

export const Loader: React.FC<LoaderProps> = ({ title, content, isOpen }) => {

    return (
        <div className="component-loader">
            <Modal isOpen={isOpen} setIsOpen={() => { }} size="sm" isCentered isStaticBackdrop isAnimation={false}>
                <ModalHeader className="justify-content-center">
                    <CardTitle >{title}</CardTitle>
                </ModalHeader>
                <ModalBody className="text-center">
                    <div className="loader-content">
                        {content}
                    </div>
                    <div className="loader-animation-container">
                        <Lottie animationData={loaderAnimation} width={200} height={200} />
                    </div>
                </ModalBody>
            </Modal>
        </div>
    )
}