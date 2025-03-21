import React from "react";

export default function Amount() {
    return (
        <div className="flex items-center justify-center p-1">
            <select
                className="text-xl rounded-r-none md:text-3xl select select-lg select-bordered border-neutral-light focus:outline-neutral-light focus:border-neutral-light"
                required
            >
                <option disabled selected hidden>
                    回数
                </option>
                <option value="onetime">一回</option>
                <option value="monthly">毎月</option>
            </select>
            <select
                className="text-xl text-center rounded-l-none md:text-3xl select select-lg select-bordered border-neutral-light focus:outline-neutral-light focus:border-neutral-light"
                required
            >
                <option disabled selected hidden>
                    寄付額
                </option>
                <option value="3000">3,000円</option>
                <option value="5000">5,000円</option>
                <option value="6000">6,000円</option>
                <option value="7000">7,000円</option>
                <option value="8000">8,000円</option>
                <option value="9000">9,000円</option>
                <option value="10000">10,000円</option>
                <option value="15000">15,000円</option>
                <option value="20000">20,000円</option>
                <option value="25000">25,000円</option>
                <option value="30000">30,000円</option>
                <option value="50000">50,000円</option>
                <option value="100000">100,000円</option>
                <option value="150000">150,000円</option>
                <option value="200000">200,000円</option>
                <option value="250000">250,000円</option>
                <option value="300000">300,000円</option>
            </select>
        </div>
    );
}
