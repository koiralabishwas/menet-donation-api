import { ReactNode } from "react";
import Header from "./header";

type Props = {
    children: ReactNode;
    className?: string;
};

export default function Layout({ children, className }: Props) {
    return (
        <>
            <Header />
            <main className={className}>{children}</main>
        </>
    );
}
