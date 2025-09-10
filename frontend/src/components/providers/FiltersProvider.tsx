import React, { createContext, useContext, useEffect } from "react";
import { convertirAQueryString, decodeQueryString } from "../../utils/urlFilters/transformUrl";
import { useLocation, useNavigate } from "react-router-dom";
import useFilters, { FilterOptions } from "../../hooks/useFilters";

interface FiltersProviderProps {
    children: React.ReactNode;
    defaultFilterFilters?: any;
}

interface FiltersContextProps {
  filters: FilterOptions;
  updateFilters: any;
  updateFilterOrder: any;
  updatePage: any;
  updatePageSize: any;
  resetFilters: any;
  checkIfUrlHasFilters: any;
}

const FiltersContext = createContext<FiltersContextProps | undefined>(undefined);

export const FiltersProvider: React.FC<FiltersProviderProps> = ({ children , defaultFilterFilters }) => {

  const navigate = useNavigate();
  const location = useLocation();
  const decodedFilters = decodeQueryString(location.search);

   // DEFAULT VALUES
   const defaultFilterOrder: any[] = [];
   const defaultLimit = 50;
 
   // OBTAING IF DECODED FILTERS FILTERS EXIST
   const decodedFiltersExist = decodedFilters && decodedFilters.filter_filters && Object.keys(decodedFilters.filter_filters).length > 0;
 
   // CONFIGURE FILTERS
   const configuredFilters = decodedFiltersExist ? decodedFilters.filter_filters : defaultFilterFilters ? defaultFilterFilters : {};
   const configuredOrder = decodedFilters ? decodedFilters.filter_order : defaultFilterOrder;
   const configuredLimit = decodedFilters ? decodedFilters.limit : defaultLimit;
 
   // FILTERS HOOK
   const { filters, updateFilters, resetFilters, updateFilterOrder, updatePage, updatePageSize } = useFilters(
     configuredFilters,
     configuredOrder,
     configuredLimit
   );

  useEffect(() => {
    const queryString = convertirAQueryString(filters); 
    navigate(`?${queryString}`, { replace: true }); 
  }, [filters]);

  const checkIfUrlHasFilters = () => {
    const urlHasFilters = location.search !== "";
    navigate(urlHasFilters ? location.pathname : `${location.pathname}?${convertirAQueryString(filters)}`, { replace: true });
  }

  const contextValue: FiltersContextProps = {
    filters,
    updateFilters,
    updateFilterOrder,
    updatePage,
    updatePageSize,
    resetFilters,
    checkIfUrlHasFilters
  };

  return (
    <FiltersContext.Provider value={contextValue}>
      {children}
    </FiltersContext.Provider>
  );
};

export const useFiltersPR = (): FiltersContextProps => {
  const context = useContext(FiltersContext);
  if (!context) {
    throw new Error("useFiltersPR must be used within a FiltersProvider");
  }
  return context;
};
