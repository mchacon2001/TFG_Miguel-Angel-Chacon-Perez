import { RestServiceConnection } from '../restServiceConnection';

const REPORTS_ENDPOINT = '/users';

export class ReportsService extends RestServiceConnection {

    /**
     * Generate a specific user report for the given period
     */
    generateUserReport = async (userId: string, period: 'weekly' | 'monthly' | 'yearly') => {
        this.response = await this.makeRequest({
            method: 'POST',
            url: REPORTS_ENDPOINT + '/generate-report',
            data: { userId, period }
        }, true);
        return this;
    }
}

