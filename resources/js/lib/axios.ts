import axios, { type AxiosInstance, type AxiosRequestConfig, type AxiosResponse } from 'axios';
// Avoid using React hooks in Vue runtime interceptors

// Create axios instance with base URL from environment variables
const axiosInstance: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || '',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true,
});

// Request interceptor to add auth token
axiosInstance.interceptors.request.use(
  (config) => {
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;
    if (token) {
      (config.headers as any).Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle errors
axiosInstance.interceptors.response.use(
  (response: AxiosResponse) => {
    return response;
  },
  (error) => {
    const notify = (title: string, description: string) => {
      if ((window as any).toastr) {
        (window as any).toastr.error(description, title);
      } else {
        console.error(`[${title}]`, description);
      }
    };
    
    // Handle network errors
    if (!error.response) {
      notify('Error de conexión', 'No se pudo conectar con el servidor. Por favor, verifica tu conexión a internet.');
      return Promise.reject(error);
    }

    const { status, data } = error.response;

    // Handle specific error statuses
    switch (status) {
      case 401:
        // Handle unauthorized (token expired, invalid, etc.)
        try { localStorage.removeItem('token'); } catch {}
        window.location.href = '/login';
        break;

      case 403:
        notify('Acceso denegado', 'No tienes permiso para realizar esta acción.');
        break;

      case 404:
        notify('No encontrado', 'El recurso solicitado no existe.');
        break;

      case 419:
        notify('Sesión expirada', 'Por favor, recarga la página e intenta de nuevo.');
        break;

      case 422:
        // Handle validation errors (handled in components)
        break;

      case 429:
        notify('Demasiadas solicitudes', 'Has excedido el límite de solicitudes. Por favor, espera un momento.');
        break;

      case 500:
        notify('Error del servidor', 'Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo más tarde.');
        break;

      default:
        // Handle other errors
        if (data.message) notify('Error', data.message);
        break;
    }

    return Promise.reject(error);
  }
);

// Helper functions for common HTTP methods
export const http = {
  get: <T>(url: string, config?: AxiosRequestConfig): Promise<AxiosResponse<T>> => {
    return axiosInstance.get<T>(url, config);
  },
  post: <T>(
    url: string,
    data?: any,
    config?: AxiosRequestConfig
  ): Promise<AxiosResponse<T>> => {
    return axiosInstance.post<T>(url, data, config);
  },
  put: <T>(
    url: string,
    data?: any,
    config?: AxiosRequestConfig
  ): Promise<AxiosResponse<T>> => {
    return axiosInstance.put<T>(url, data, config);
  },
  patch: <T>(
    url: string,
    data?: any,
    config?: AxiosRequestConfig
  ): Promise<AxiosResponse<T>> => {
    return axiosInstance.patch<T>(url, data, config);
  },
  delete: <T>(url: string, config?: AxiosRequestConfig): Promise<AxiosResponse<T>> => {
    return axiosInstance.delete<T>(url, config);
  },
};

export default axiosInstance;
