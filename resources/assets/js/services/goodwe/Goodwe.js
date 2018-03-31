import axios from "axios";

class Goodwe {
  async current(id) {
    const response = await this.fetchResults(id);

    return response.data;
  }

  async fetchResults(id) {
    const endpoint = `http://www.goodwe-power.com/Mobile/GetMyPowerStationById?stationID=${id}`;

    return await axios.get(endpoint);
  }
}

export default new Goodwe();
