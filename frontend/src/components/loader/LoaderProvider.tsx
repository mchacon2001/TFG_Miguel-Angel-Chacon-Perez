import React, { useContext, useState } from "react";
import { Loader } from "./Loader";

type LoaderContextType = {
    isLoading: boolean,
    showLoading: (title: string, content?: React.ReactElement) => void,
    hideLoading: () => void
}

const LoaderContext = React.createContext<LoaderContextType>({
    isLoading: false,
    showLoading: (title: string, content?: React.ReactElement) => { },
    hideLoading: () => { }
});

type LoaderProviderProps = {
    children: React.ReactNode
}

const LoaderProvider: React.FC<LoaderProviderProps> = ({ children }) => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [title, setTitle] = useState<string>('');
    const [content, setContent] = useState<React.ReactElement | undefined>(undefined);

    const showLoading = (title: string, content?: React.ReactElement) => {
        setTitle(title);
        setContent(content);
        setIsLoading(true);
    }

    const hideLoading = () => {
        setIsLoading(false);
    }

    return (
        <LoaderContext.Provider value={{ isLoading, showLoading, hideLoading }}>
            <Loader title={title} content={content} isOpen={isLoading} />
            {children}
        </LoaderContext.Provider>
    )
}

export function useLoader() {
    return useContext(LoaderContext);
}

export { LoaderProvider, LoaderContext }