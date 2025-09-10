export type CallInfo = {
    callId?:      string;
    agentId?:     string;
    phoneNumber?: string;
    data?:        Data;
}

export type Data = {
    callId?:     string;
    parameters?: Parameters<string>;
}

export type Parameters<T> = {
    [key: string]: T;
}

