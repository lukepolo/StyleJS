export default class Loading {
  
  private timeoutSet = false;
  private requestsCounter = 0;
  private spinnerElement = null;

  public request(config: object) {
    if (this.timeoutSet === false) {
      this._turnOnSpinner();
      this.timeoutSet = true;
    }

    this.requestsCounter++;

    return config;
  }

  public response(response: object) {
    if (--this.requestsCounter === 0) {
      this._turnOffSpinner();
    }

    return response;
  }

  public responseError(error) {
    if (--this.requestsCounter === 0) {
      this._turnOffSpinner();
    }
    return Promise.reject(error);
  }

  private _turnOnSpinner() {
    if (!this.spinnerElement) {
      let spinner = document.createElement("div");
      spinner.id = "app-spinner";
      spinner.classList.add("sk-container");

      let cube = document.createElement("div");
      cube.classList.add("sk-folding-cube");
      for (let i = 1; i <= 4; i++) {
        let cubePart = document.createElement("div");
        cubePart.classList.add(`sk-cube`);
        cubePart.classList.add(`sk-cube${i}`);
        cube.appendChild(cubePart);
      }
      spinner.appendChild(cube);

      this.spinnerElement = spinner;

      document.body.appendChild(spinner);
    }
  }

  private _turnOffSpinner() {
    this.timeoutSet = false;
    if (this.spinnerElement) {
      this.spinnerElement.remove();
      this.spinnerElement = null;
    }
  }
}
