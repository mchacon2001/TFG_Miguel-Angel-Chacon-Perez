import { useState, useEffect } from 'react';
import { ApiResponse } from '../type/apiResponse-type';

const useFetch = (fetchFunction: () => Promise<any>) => {

    const [reloading, setReloading] = useState(false);
    const [data, setData] = useState<any>(null);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<unknown>(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const response = await fetchFunction() as ApiResponse;
                if (response.success || response.data) {
                    setData(response.data);
                } else {
                    setData(null);
                    setError(new Error(response.message as string));
                }
                setError(null);
            } catch (error: any) {
                setData(null);
                setError(error);
            } finally {
                setLoading(false);
                reloading && setReloading(false);
            }
        }
        fetchData();
    }, [fetchFunction, reloading]);

    const refetch = () => {
        setReloading(true);
    }

    return [data, loading, error, refetch];
}

export default useFetch;
