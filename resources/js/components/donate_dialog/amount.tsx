import React from "react";

export default function Amount() {
    return (
        <div className="flex items-center justify-center p-1">
            <select
                className="rounded-r-none md:text-3xl select select-lg select-bordered border-neutral-light focus:outline-neutral-light focus:border-neutral-light"
                required
            >
                <option disabled selected hidden>
                    回数
                </option>
                <option value="onetime">一回</option>
                <option value="monthly">毎月</option>
            </select>
            <select
                className="text-center rounded-l-none md:text-3xl select select-lg select-bordered border-neutral-light focus:outline-neutral-light focus:border-neutral-light"
                required
            >
                <option disabled selected hidden>
                    寄付額
                </option>
                <option value="3000">3000円</option>
                <option value="5000">5000円</option>
                <option value="6000">6000円</option>
                <option value="7000">7000円</option>
                <option value="8000">8000円</option>
                <option value="9000">9000円</option>
                <option value="10000">10000円</option>
                <option value="15000">15000円</option>
                <option value="20000">20000円</option>
                <option value="25000">25000円</option>
                <option value="30000">30000円</option>
                <option value="50000">50000円</option>
                <option value="100000">100000円</option>
            </select>
        </div>
    );
}
