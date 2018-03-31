import axios from "axios";

class Goodwe {
  async current(id, url) {
    const response = await this.fetchResults(id, url);

    return response.data;
  }

  async fetchResults(id, url) {
    const endpoint = `${url}/api/goodwe`;

    return await axios.get(endpoint);
  }
}

export default new Goodwe();
