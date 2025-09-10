import { FC, Fragment, useContext } from "react"
import { Navigate, Outlet } from "react-router-dom"
import PageWrapper from "../../layout/PageWrapper/PageWrapper"
import DefaultAside from "./_asides/DefaultAside"
import DefaultFooter from "./_footers/DefaultFooter"
import { useSelector } from "react-redux"
import { RootState } from "../../redux/store"
import ThemeContext from "../../contexts/themeContext"

interface AppLayoutProps {
    children?: React.ReactNode
}

const AppLayout: FC<AppLayoutProps> = ({children}) => {
    
    const { isAuthenticated } = useSelector((state: RootState) => state.auth);
    const { asideStatus } = useContext(ThemeContext);
    if (isAuthenticated) {
        return (
            <Fragment> 
                <DefaultAside/>
                <div className="wrapper">
                   <main className={`content ${!asideStatus ? 'aside-minimized' : ''}`}> 
                        <PageWrapper>
                            <Outlet/>
                        </PageWrapper>
                    </main>
                    <DefaultFooter/>
                </div>
            </Fragment>
        )
    }else{
        return (
            <Navigate to="/login" />
        )
    }
}

export default AppLayout