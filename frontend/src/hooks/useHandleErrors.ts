import { toast } from "react-toastify";

export default function useHandleErrors() {

  const handleErrors = (response: any) => {
    if (!response.success) {
      if (response.data?.errors) {
        response.data.errors.forEach((error: any) => {
          toast.error(/* error.property + ": " + */ error.message);
        });
      } else {
        toast.error(response.message);
      }
    }
  }

  return { handleErrors };
}